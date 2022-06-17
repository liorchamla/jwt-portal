import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ApplicationAccountsComponent } from './application-accounts.component';
import { ApplicationDetailsFormComponent } from './application-details-form.component';
import { ApplicationRoutesFormComponent } from './application-routes-form.component';
import { ApplicationComponent } from './application.component';

const routes: Routes = [
  {
    path: 'create',
    component: ApplicationComponent,
    children: [
      { path: '', component: ApplicationDetailsFormComponent },
      { path: 'routes', component: ApplicationRoutesFormComponent },
    ],
  },
  {
    path: ':id',
    component: ApplicationComponent,
    children: [
      { path: '', component: ApplicationDetailsFormComponent },
      { path: 'routes', component: ApplicationRoutesFormComponent },
      { path: 'accounts', component: ApplicationAccountsComponent },
    ],
  },
];
@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ApplicationRoutingModule {}
