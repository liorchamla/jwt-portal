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
      {
        path: '',
        component: ApplicationDetailsFormComponent,
        data: {
          title: "Setup application's data",
        },
      },
      {
        path: 'routes',
        component: ApplicationRoutesFormComponent,
        data: {
          title: "Setup application's routes",
        },
      },
    ],
  },
  {
    path: ':slug',
    component: ApplicationComponent,
    children: [
      {
        path: '',
        component: ApplicationDetailsFormComponent,
        data: {
          title: "Edit application's data",
        },
      },
      {
        path: 'routes',
        component: ApplicationRoutesFormComponent,
        data: {
          title: "Edit application's routes",
        },
      },
      {
        path: 'accounts',
        component: ApplicationAccountsComponent,
        data: {
          title: "See application's accounts",
        },
      },
    ],
  },
];
@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ApplicationRoutingModule {}
