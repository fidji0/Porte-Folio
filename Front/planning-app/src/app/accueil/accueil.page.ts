import { Component, OnInit } from '@angular/core';
import { BehaviorSubject, combineLatest, map, Observable, shareReplay } from 'rxjs';
import { GroupedShifts, Shift } from '../class/shift';
import { ScheduleService } from '../services/schedule-service.service';
import { AuthService } from '../services/auth.service';
import { ModalController } from '@ionic/angular';
import { EventDetailModalComponent } from './event-detail-modal/event-detail-modal.component';

@Component({
  selector: 'app-accueil',
  templateUrl: './accueil.page.html',
  styleUrls: ['./accueil.page.scss'],
})
export class AccueilPage implements OnInit {
  
  private allShiftsSubject = new BehaviorSubject<Shift[]>([]);
  private currentWeekSubject = new BehaviorSubject<Date>(new Date());
  public EVENT_TYPES = {
    TRAVAIL: { name: 'Travail', color: '#4CAF50' },
    DEPLACEMENT: { name: 'Déplacement', color: '#2196F3' },
    CONGES: { name: 'Congés', color: '#FF9800' },
    MALADIE: { name: 'Maladie', color: '#F44336' },
    FORMATION: { name: 'Formation', color: '#9C27B0' },
    AUTRE: { name: 'Autre', color: '#607D8B' }
  };

  currentWeek$ = this.currentWeekSubject.asObservable();


  filteredSchedule$: Observable<GroupedShifts[]>;
  weekLabel$: Observable<string>;

  constructor(public scheduleService: ScheduleService, public auth: AuthService,
    private modalController: ModalController) { }

  ngOnInit() {



    this.weekLabel$ = this.currentWeek$.pipe(
      map(date => {
        const start = this.formatDate(this.getStartOfWeek(date));
        const end = this.formatDate(this.getEndOfWeek(date));
        return `${start} - ${end}`;
      })
    );

    this.filteredSchedule$ = combineLatest([
      this.scheduleService.allShiftsSubject,
      this.currentWeek$
    ]).pipe(
      map(([allShifts, currentWeek]) =>
        this.groupShiftsByDate(
          this.filterShiftsForWeek(allShifts, currentWeek)
            .filter(shift => shift.employe_id === this.auth.user.id)
        )
      ),
      shareReplay(1)
    );
  }
  doRefresh(event: any) {
    this.scheduleService.getWeekSchedule().subscribe()
        event.target.complete();
     
  }
  private groupShiftsByDate(shifts: Shift[]): GroupedShifts[] {
    const groupedShifts = new Map<string, Shift[]>();

    shifts.forEach(shift => {
      const dateKey = new Date(shift.start_date).toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric', month: 'long' });
      if (!groupedShifts.has(dateKey)) {
        groupedShifts.set(dateKey, []);
      }
      groupedShifts.get(dateKey)!.push(shift);
    });

    return Array.from(groupedShifts.entries())
      .map(([date, shifts]) => ({
        date,
        shifts: shifts.sort((a, b) => new Date(a.start_date).getTime() - new Date(b.start_date).getTime())
      }))
      .sort((a, b) => {
        const dateA = this.parseFrenchDate(a.date);
        const dateB = this.parseFrenchDate(b.date);
        return dateA.getTime() - dateB.getTime();
      });
  }
  private parseFrenchDate(frenchDate: string): Date {
    const parts = frenchDate.split(' ');
    const day = parseInt(parts[1], 10);
    const month = this.getFrenchMonthNumber(parts[2]);
    const year = new Date().getFullYear(); // Assuming current year
    return new Date(year, month, day);
  }
  async openEventDetail(shift: Shift) {
    const modal = await this.modalController.create({
      component: EventDetailModalComponent,
      componentProps: {
        shift: shift,
        eventTypes: this.EVENT_TYPES
      }
    });
    return await modal.present();
  }
  // Méthode auxiliaire pour obtenir le numéro du mois à partir du nom français
  private getFrenchMonthNumber(monthName: string): number {
    const months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    return months.indexOf(monthName.toLowerCase());
  }
  



  previousWeek() {
    const prevWeek = new Date(this.currentWeekSubject.value);
    prevWeek.setDate(prevWeek.getDate() - 7);
    this.currentWeekSubject.next(prevWeek);
  }

  nextWeek() {
    const nextWeek = new Date(this.currentWeekSubject.value);
    nextWeek.setDate(nextWeek.getDate() + 7);
    this.currentWeekSubject.next(nextWeek);
  }


  private filterShiftsForWeek(shifts: Shift[], weekStart: Date): Shift[] {
    const weekStartDate = this.getStartOfWeek(weekStart);
    const weekEndDate = this.getEndOfWeek(weekStart);
    return shifts.filter(shift => {
      const shiftDate = new Date(shift.start_date);
      return shiftDate >= new Date(weekStartDate) &&
        shiftDate <= new Date(weekEndDate) &&
        shift.employe_id == this.auth.user.id;
    });
  }
  private getStartOfWeek(date: Date): string {
    const d = new Date(date);
    const day = d.getDay();
    const diff = d.getDate() - day + (day === 0 ? -6 : 1);
    return new Date(d.setDate(diff)).toISOString().split('T')[0];
  }
  getEventTypeColor(type: string): string {
    return this.EVENT_TYPES[type]?.color || this.EVENT_TYPES.AUTRE.color;
  }

  
  private getEndOfWeek(date: Date): string {
    const start = new Date(this.getStartOfWeek(date));
    const end = new Date(start);
    end.setDate(end.getDate() + 6);
    return end.toISOString().split('T')[0];
  }

  private formatDate(dateString: string): string {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
  }
  calculateDuration(startDate: string, endDate: string): string {
    const start = new Date(startDate);
    const end = new Date(endDate);

    let diff = end.getTime() - start.getTime();

    // Gérer le cas où le shift se termine après minuit
    if (diff < 0) {
      diff += 24 * 60 * 60 * 1000; // Ajouter 24 heures
    }

    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

    if (hours === 0) {
      return `${minutes}min`;
    } else if (minutes === 0) {
      return `${hours}h`;
    } else {
      return `${hours}h${minutes}min`;
    }
  }
  getShiftTypeClass(type: string): string {
    return type === 'TRAVAIL' ? 'shift-midi' : 'shift-soir';
  }

}
