// src/app/pages/auth/auth.page.ts

import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

import { Router } from '@angular/router';
import { AuthService } from '../services/auth.service';
import { AlertController } from '@ionic/angular';
import { ScheduleService } from '../services/schedule-service.service';

@Component({
  selector: 'app-auth',
  templateUrl: './auth.page.html',
  styleUrls: ['./auth.page.scss'],
})
export class AuthPage {
  authForm: FormGroup;
  token : string = null;
  
  constructor(
    private formBuilder: FormBuilder,
    public authService: AuthService,
    private router: Router,
    private alertController: AlertController,
    private schedule : ScheduleService
  ) {
    this.authService.getNotifId().then(res => {
      this.token = res
    })
    this.authForm = this.formBuilder.group({
      email: [this.authService.userConnexion?.email ?? " ", [Validators.required, Validators.email]],
      password: [this.authService.userConnexion?.password ?? '', [Validators.required, Validators.minLength(4)]],
      sct_code: [this.authService.userConnexion?.sct_code ?? '', [Validators.required]]
      
      
    });
    
  }
  logout () {
    this.authService.logout()
  }
  async onSubmit() {
    if (this.authForm.valid) {
      
      try {
        this.authForm.value.notif_phone_id = this.authService.userConnexion?.notif_phone_id
        this.authService.userConnexion.email = this.authForm.value.email
        this.authService.userConnexion.password = this.authForm.value.password
        this.authService.userConnexion.sct_code = this.authForm.value.sct_code
        const success = await this.authService.login(this.authForm.value);
        if (success) {
          await this.authService.setUserConnexion()
          this.schedule.getAbsence().subscribe()
          this.schedule.getNotif().subscribe()
          this.schedule.getWeekSchedule().subscribe()
          this.router.navigate(['/accueil']);
        } else {
          this.presentAlert("Erreur de connexion" , 
            "L'identifiant ou le mot de passe est invalide"
          ).then()
          // Gérer l'échec de connexion (par exemple, afficher un message d'erreur)
        }
      } catch (error) {
        console.error('Erreur de connexion', error);
        
        // Gérer l'erreur (par exemple, afficher un message d'erreur)
      }
    }else{
      this.presentAlert("Formulaire invalide" , 
        "Merci de completer votre formulaire de connexion"
      ).then()
    }
  }
  async presentAlert(header : string , message : string) {
    const alert = await this.alertController.create({
      header: header,
      message: message,
      buttons: ['Ok'],
    });

    await alert.present();
  }
  
}