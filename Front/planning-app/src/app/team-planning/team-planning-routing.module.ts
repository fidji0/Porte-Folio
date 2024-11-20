import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { TeamPlanningPage } from './team-planning.page';

const routes: Routes = [
  {
    path: '',
    component: TeamPlanningPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class TeamPlanningPageRoutingModule {}
