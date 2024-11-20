import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { AbsenceRequestPageRoutingModule } from './absence-request-routing.module';

import { AbsenceRequestPage } from './absence-request.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    AbsenceRequestPageRoutingModule,
    ReactiveFormsModule
  ],
  declarations: [AbsenceRequestPage]
})
export class AbsenceRequestPageModule {}
