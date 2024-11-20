import { Component, OnInit } from '@angular/core';
import { AlertController, Platform } from '@ionic/angular';
import { App } from '@capacitor/app';
import { ActionPerformed, PushNotifications, PushNotificationSchema, Token } from '@capacitor/push-notifications';
import { AuthService } from './services/auth.service';


import { ScheduleService } from './services/schedule-service.service';
import { BadgeService } from './services/badge.service';
import { Notif } from './class/shift';

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent implements OnInit {
  actualVersion = 160;
  constructor(
    private auth: AuthService,
    public schedule: ScheduleService,
    private platform: Platform,
    private badge: BadgeService,
    private alertController: AlertController
  ) { }

  ngOnInit() {


    this.badge.requestPermissionsBadge()
    this.verifyVersion()
    this.refreshAllData()
    this.platform.ready().then(() => {
      this.initializePushNotifications();
      App.addListener('appStateChange', (state) => {
        if (state.isActive) {
          this.refreshAllData()
        }

      });
    });

  }
  refreshAllData(){
this.auth.checkToken().then(res => {
          
            if (this.auth.isAuthenticated()) {
              this.verifyVersion()
              this.schedule.getAbsence().subscribe()
              this.schedule.getNotif().subscribe(res => {
                this.schedule.updateUnreadNotificationsCount().then(res => {
                  this.badge.setBadge(this.schedule.unreadNotificationsCount)
                })
              })
              this.schedule.getWeekSchedule().subscribe()
              this.schedule.getAbsence().subscribe( );
            }
          }
        
        )
  }
  verifyVersion() {
    this.schedule.getVersion().subscribe(res => {
      if (res.version >= this.actualVersion) {
        this.presentAlert("Mettre à jour votre application", "Merci de mettre à jour votre application")
      }
    })
  }

  async presentAlert(header: string, message: string) {
    const alert = await this.alertController.create({
      header: header,
      message: message,
      buttons: ['Ok'],
    });

    await alert.present();
  }
  private initializePushNotifications() {
    PushNotifications.requestPermissions().then(result => {
      console.log(result.receive);

      if (result.receive === 'granted') {
        PushNotifications.register();
      } else {
        console.log("Permission refusée pour les notifications");
        
        PushNotifications.register();
        
      }
    }).catch(err => {
      console.error("Erreur lors de la demande de permission:", err);
    });

    PushNotifications.addListener('registration', (token: Token) => {

      this.auth.setNotifId(token.value);
      this.auth.userConnexion.notif_phone_id = token.value;

    });

    PushNotifications.addListener('registrationError', (error: any) => {
      //console.error("Erreur d'enregistrement des notifications:", error);

    });

    PushNotifications.addListener('pushNotificationReceived',
      (notification: PushNotificationSchema) => {

        this.schedule.getAbsence().subscribe()
        this.schedule.getNotif().subscribe()
        this.schedule.getWeekSchedule().subscribe()

      });

    PushNotifications.addListener('pushNotificationActionPerformed',
      (notification: ActionPerformed) => {
        console.log("Action effectuée sur la notification:", notification);
        // Traitez l'action effectuée sur la notification ici
        // Par exemple, naviguer vers une page spécifique
        // this.router.navigate(['/some-page']);
      });
  }

  private setupAppUrlListener() {
    App.addListener('appUrlOpen', (data: { url: string }) => {
      console.log('App opened with URL:', data.url);
      // Traitez l'URL d'ouverture de l'application ici
      // Par exemple, extraire des paramètres et naviguer en conséquence
      // const segments = new URL(data.url).pathname.split('/');
      // if (segments[1] === 'some-path') {
      //   this.router.navigate(['/some-page', segments[2]]);
      // }
    });
  }



  // Méthode pour obtenir le nombre actuel du badge

}