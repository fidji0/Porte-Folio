import { Component, OnInit } from '@angular/core';
import { ScheduleService } from '../services/schedule-service.service';
import { Notif } from '../class/shift';
import { BadgeService } from '../services/badge.service';

@Component({
  selector: 'app-historique',
  templateUrl: './historique.page.html',
  styleUrls: ['./historique.page.scss'],
})
export class HistoriquePage implements OnInit {
  notifications: Notif[] = [];

  constructor(public notif: ScheduleService, private badge: BadgeService) { }

  ngOnInit() {    
    this.notif.getAllNotifications().subscribe((notifs: Notif[]) => {
      this.notifications = notifs;   
      
         
    });
  }

  ionViewWillEnter() {
    if (this.notif.unreadNotificationsCount > 0) {

      this.notif.updateNotifRead().subscribe(res => {
        this.notif.getNotif().subscribe(res => {
          this.notif.updateUnreadNotificationsCount()
          this.badge.setBadge(this.notif.unreadNotificationsCount)
        })
      });
    }
  }
  doRefresh(event) {
    // Si vous avez une méthode de rafraîchissement dans le service
    this.notif.getNotif().subscribe({
      next: value => event.target.complete(),
      error: err => event.target.complete(),
      complete: () => event.target.complete()

    });

  }

}
