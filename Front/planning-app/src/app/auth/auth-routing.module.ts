import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { AuthPage } from './auth.page';
import { EnterpriseComponent } from './enterprise/enterprise.component';
import { PersoComponent } from './perso/perso.component';

const routes: Routes = [
  {
    path: '',
    component: AuthPage
  },
  {
    path: 'enterprise',
    component: EnterpriseComponent
  },
  {
    path: 'perso',
    component: PersoComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AuthPageRoutingModule {}
