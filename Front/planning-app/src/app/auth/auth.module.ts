import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { AuthPageRoutingModule } from './auth-routing.module';

import { AuthPage } from './auth.page';
import { PersoComponent } from './perso/perso.component';
import { EnterpriseComponent } from './enterprise/enterprise.component';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    AuthPageRoutingModule,
    ReactiveFormsModule
    ],
  declarations: [AuthPage , PersoComponent , EnterpriseComponent]
})
export class AuthPageModule {}
