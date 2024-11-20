import { Component, OnInit, ViewChild, ElementRef, AfterViewInit } from '@angular/core';
import { BehaviorSubject, combineLatest, Observable } from 'rxjs';
import { delay, map, switchMap, take } from 'rxjs/operators';
import { ScheduleService } from '../services/schedule-service.service';
import { Shift } from '../class/shift';
import { ModalController } from '@ionic/angular';
import { EventDetailModalComponent } from '../accueil/event-detail-modal/event-detail-modal.component';
import { AuthService } from '../services/auth.service';

interface InfoJour {
  date: Date;
  nomJour: string;
  numeroJour: number;
  estAujourdhui: boolean;
}

interface QuartsTravailGroupes {
  personne: string;
  quarts: Shift[];
}

@Component({
  selector: 'app-team-planning',
  templateUrl: './team-planning.page.html',
  styleUrls: ['./team-planning.page.scss'],
})
export class TeamPlanningPage implements OnInit, AfterViewInit {
  @ViewChild('timelineContainer', { static: false }) timelineContainer: ElementRef;

  private moisCourantSubject = new BehaviorSubject<Date>(new Date());
  moisCourant$: Observable<string>;
  joursduMois$: Observable<InfoJour[]>;
  dateSelectionnee$ = new BehaviorSubject<Date>(new Date());
  quartsFiltres$: Observable<QuartsTravailGroupes[]>;

  public TYPES_EVENEMENTS = {
    TRAVAIL: { nom: 'Travail', couleur: '#4CAF50' },
    DEPLACEMENT: { nom: 'Déplacement', couleur: '#2196F3' },
    CONGES: { nom: 'Congés', couleur: '#FF9800' },
    MALADIE: { nom: 'Maladie', couleur: '#F44336' },
    FORMATION: { nom: 'Formation', couleur: '#9C27B0' },
    AUTRE: { nom: 'Autre', couleur: '#607D8B' }
  };

  constructor(private event: ScheduleService, private modaleController: ModalController, public auth: AuthService) { }

  ngOnInit() {
    this.initialiserObservableMois();
    this.initialiserObservableQuarts();
  }

  
  ngAfterViewInit() {
    setTimeout(() => {
      const aujourd = new Date();
      this.selectionnerDate(aujourd);
    }, 100);


  }

  private initialiserObservableMois() {
    this.moisCourant$ = this.moisCourantSubject.pipe(
      map(date => this.formaterMois(date))
    );

    this.joursduMois$ = this.moisCourantSubject.pipe(
      map(date => this.obtenirJoursDuMois(date)),
      delay(0) // Ajoute un délai pour assurer que l'Observable émet après le changement de détection
    );
  }

  private obtenirJoursDuMois(date: Date): InfoJour[] {
    const annee = date.getFullYear();
    const mois = date.getMonth();
    const joursEnMois = new Date(annee, mois + 1, 0).getDate();
    return Array.from({ length: joursEnMois }, (_, i) => {
      const jour = new Date(annee, mois, i + 1);
      return {
        date: jour,
        nomJour: jour.toLocaleDateString('fr-FR', { weekday: 'short' }),
        numeroJour: i + 1,
        estAujourdhui: this.estAujourdhui(jour)
      };
    });
  }

  private estAujourdhui(date: Date): boolean {
    const aujourdhui = new Date();
    return date.getDate() === aujourdhui.getDate() &&
      date.getMonth() === aujourdhui.getMonth() &&
      date.getFullYear() === aujourdhui.getFullYear();
  }

  selectionnerDate(date: Date) {
    this.dateSelectionnee$.next(date);
    this.defilementVersDateSelectionnee(date);
  }

  defilementVersDateSelectionnee(date: Date) {
    setTimeout(() => {
      const elementSelectionne = this.timelineContainer.nativeElement.querySelector('.selected');
      if (elementSelectionne) {
        elementSelectionne.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });
      }
    }, 100); // Délai de 100 ms
}




  moisSuivant() {
    const moisSuivant = new Date(this.moisCourantSubject.value);
    moisSuivant.setMonth(moisSuivant.getMonth() + 1);
    this.moisCourantSubject.next(moisSuivant);
  
    // Mettre à jour la date sélectionnée au premier jour du mois suivant
    const premiereDateMoisSuivant = new Date(moisSuivant.getFullYear(), moisSuivant.getMonth(), 1);
    this.selectionnerDate(premiereDateMoisSuivant);
  }
  
  moisPrecedent() {
    const moisPrecedent = new Date(this.moisCourantSubject.value);
    moisPrecedent.setMonth(moisPrecedent.getMonth() - 1);
    this.moisCourantSubject.next(moisPrecedent);
  
    // Obtenir le dernier jour du mois précédent
    const dernierJourMoisPrecedent = new Date(moisPrecedent.getFullYear(), moisPrecedent.getMonth() + 1, 0);
    this.selectionnerDate(dernierJourMoisPrecedent);
  }
  
  

  obtenirCouleurTypeEvenement(type: string): string {
    return this.TYPES_EVENEMENTS[type]?.couleur || this.TYPES_EVENEMENTS.AUTRE.couleur;
  }

  private filtrerQuartsPourDate(quarts: Shift[], date: Date): Shift[] {
    const debutJour = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    const finJour = new Date(date.getFullYear(), date.getMonth(), date.getDate(), 23, 59, 59);
  
    return quarts.filter(quart => {
      const dateDebutQuart = new Date(quart.start_date);
      const dateFinQuart = new Date(quart.end_date);
  
      return (dateDebutQuart <= finJour && dateFinQuart >= debutJour) || 
             (dateDebutQuart.getDate() === date.getDate() &&
              dateDebutQuart.getMonth() === date.getMonth() &&
              dateDebutQuart.getFullYear() === date.getFullYear());
    });
  }

  private initialiserObservableQuarts() {   
    this.quartsFiltres$ = combineLatest([
      this.event.allShiftsSubject,
      this.dateSelectionnee$
    ]).pipe(
      map(([quarts, dateSelectionnee]) => {
        const quartsFiltres = this.filtrerQuartsPourDate(quarts, dateSelectionnee);
        return this.regrouperQuartsParPersonne(quartsFiltres);
      })
    );
  }
  doRefresh(event: any) {
    // Recharger les shifts
    this.initialiserObservableMois();
    this.event.getWeekSchedule().subscribe(res =>
      this.initialiserObservableQuarts()
    )
    event.target.complete();
     
  }
  private regrouperQuartsParPersonne(quarts: Shift[]): QuartsTravailGroupes[] {
    const quartsGroupes: { [key: string]: Shift[] } = quarts.reduce((groupes, quart) => {
      const nomComplet = `${quart.surname} ${quart.name}`;
      if (!groupes[nomComplet]) {
        groupes[nomComplet] = [];
      }
      groupes[nomComplet].push(quart);
      return groupes;
    }, {} as { [key: string]: Shift[] });
  
    let quartsGroupesArray = Object.entries(quartsGroupes).map(([personne, quartsPersonne]) => ({
      personne,
      quarts: quartsPersonne.sort((a, b) => 
        new Date(a.start_date).getTime() - new Date(b.start_date).getTime()
      ),
      employeeId: quartsPersonne[0].employe_id // Récupérer l'employee_id
    }));
  
    // Trier pour que l'utilisateur connecté soit en premier
    const utilisateurConnecte = quartsGroupesArray.filter(groupe => groupe.quarts[0].employe_id === this.auth.user.id);
    const autresUtilisateurs = quartsGroupesArray.filter(groupe => groupe.quarts[0].employe_id !== this.auth.user.id);
  
    // Retourner l'utilisateur connecté en premier, suivi des autres
    return [...utilisateurConnecte, ...autresUtilisateurs];
  }
  

  formaterHeure(chaineDate: string): string {
    const date = new Date(chaineDate);
    return date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
  }

  private formaterMois(date: Date): string {
    return date.toLocaleString('fr-FR', { month: 'long', year: 'numeric' });
  }

  async ouvrirDetailEvenement(quart: Shift) {
    const modal = await this.modaleController.create({
      component: EventDetailModalComponent,
      componentProps: {
        shift: quart,
        eventTypes: this.TYPES_EVENEMENTS
      }
    });
    return await modal.present();
  }
}