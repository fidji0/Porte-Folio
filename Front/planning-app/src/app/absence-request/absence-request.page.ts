import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ScheduleService } from '../services/schedule-service.service';
import { Absence } from '../class/shift';
import { AlertController } from '@ionic/angular';

@Component({
  selector: 'app-absence-request',
  templateUrl: './absence-request.page.html',
  styleUrls: ['./absence-request.page.scss'],
})
export class AbsenceRequestPage implements OnInit {
  absenceForm: FormGroup;
  segmentModel = 'en attente';
  absenceTypes = [
    'Congés payés',
    'Maladie',
    'Congé sans solde',
    'Formation',
    'Autre',
    'Travail'
  ];
  absences: Absence[] = [];
  absencesGroupees: { [key: string]: Absence[] } = {
    'En attente': [],
    'Validé': [],
    'Refusé': []
  };

  constructor(private formBuilder: FormBuilder, private scheduleService: ScheduleService, private alertController: AlertController) { }

  ngOnInit() {
    this.absenceForm = this.formBuilder.group({
      start_date: ['', Validators.required],
      end_date: ['', Validators.required],
      objet: ['', Validators.required],
      details: ['', Validators.required],
      lieu: ['Inconnu']
    });
    this.scheduleService.getAllAbsence().subscribe((absences: Absence[]) => {
      this.absences = absences;      
      this.grouperAbsences()
    });
  }

  onSubmit() {
    let typeall = {
      'Congés payés': "CONGES",
      'Maladie': 'MALADIE',
      'Congé sans solde': 'AUTRE',
      'Formation': "FORMATION",
      'Autre': "AUTRE",
      'Travail': "TRAVAIL"
    };
    if (this.absenceForm.value.start_date >= this.absenceForm.value.end_date) {
      this.presentAlert("Erreur date", "La date de fin doit être aprés celle de début")
      return;
    }
    this.absenceForm.value.type = typeall[this.absenceForm.value.objet]
    if (this.absenceForm.valid) {
      this.scheduleService.setAbsence(this.absenceForm).subscribe(res => {
        this.presentAlert("Envoye avec succès", "Votre demande à bien été envoyé")
        this.readAllAbsence();  // Rafraîchir la liste après soumission
      });
    }
  }

  deleteAbsence(id: string) {
    this.scheduleService.deleteAbsence(id).subscribe(
      {
        next: value => this.presentAlert("Suppression de votre demande" , "Votre demande à bien été supprimer"),
        error: err => this.presentAlert("Erreur suppression de votre demande" , "Une Erreur c'est produite"),
        complete: () => { this.readAllAbsence()}

      }
    )
  }

  readAllAbsence() {
      this.scheduleService.getAbsence().subscribe(
        (res: Absence[]) => {
          this.absences = res;
          this.grouperAbsences();
        }
      );
  }

  
  grouperAbsences() {
    this.absencesGroupees = {
      'en attente': [],
      'valide': [],
      'refuse': []
    };

    this.absences.forEach(absence => {
      if (absence.etat === 'en attente') {
        this.absencesGroupees['en attente'].push(absence);
      } else if (absence.etat === 'valide') {
        this.absencesGroupees['valide'].push(absence);
      } else if (absence.etat === 'refuse') {
        this.absencesGroupees['refuse'].push(absence);
      }
    });
  }

  formatDate(dateString: string): string {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric', hour: "numeric", minute: "numeric" });
  }

  async presentAlert(header: string, message: string) {
    const alert = await this.alertController.create({
      header: header,
      message: message,
      buttons: ['Ok'],
    });

    await alert.present();
  }
  async confirmDelete(id : string) {
    const alert = await this.alertController.create({
      header: 'Confirmation',
      message: 'Êtes-vous sûr de vouloir supprimer cet élément ?',
      buttons: [
        {
          text: 'Annuler',
          role: 'cancel',
          cssClass: 'secondary',
          handler: () => {
            
          }
        }, {
          text: 'Supprimer',
          handler: () => {
            this.deleteAbsence(id); // Appelle la méthode de suppression si l'utilisateur confirme
          }
        }
      ]
    });

    await alert.present();
  }
  doRefresh(event) {
    // Si vous avez une méthode de rafraîchissement dans le service
    this.scheduleService.getAbsence().subscribe({
      next: value => event.target.complete(),
      error: err => event.target.complete(),
      complete: () => event.target.complete()

    });

  }
}