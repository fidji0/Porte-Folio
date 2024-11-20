import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { TeamPlanningPageRoutingModule } from './team-planning-routing.module';

import { TeamPlanningPage } from './team-planning.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    TeamPlanningPageRoutingModule
  ],
  declarations: [TeamPlanningPage]
})
export class TeamPlanningPageModule {}
