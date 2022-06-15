import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AccountsComponent } from './pages/applications/create-or-edit/accounts.component';
import { ApplicationFormComponent } from './pages/applications/create-or-edit/application-form.component';
import { CreateOrEditApplicationComponent } from './pages/applications/create-or-edit/create-or-edit-application.component';
import { RoutesFormComponent } from './pages/applications/create-or-edit/routes-form.component';
import { HomeComponent } from './pages/home/home.component';
import { LoginComponent } from './pages/login/login.component';
import { RegisterComponent } from './pages/register/register.component';

const routes: Routes = [
  {
    path: '',
    component: HomeComponent,
  },
  { path: 'register', component: RegisterComponent },
  { path: 'login', component: LoginComponent },
  {
    path: 'applications/create',
    component: CreateOrEditApplicationComponent,
    children: [
      { path: '', component: ApplicationFormComponent },
      { path: 'routes', component: RoutesFormComponent },
    ],
  },
  {
    path: 'applications/:id',
    component: CreateOrEditApplicationComponent,
    children: [
      { path: '', component: ApplicationFormComponent },
      { path: 'routes', component: RoutesFormComponent },
      { path: 'accounts', component: AccountsComponent },
    ],
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
