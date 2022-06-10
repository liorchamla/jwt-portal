import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { NavbarComponent } from './components/navbar.component';
import { HomeComponent } from './pages/home/home.component';
import { RegisterComponent } from './pages/register/register.component';
import { InputComponent } from './components/form/input.component';
import { RegisterFormComponent } from './pages/register/register-form.component';
import { PageComponent } from './components/page.component';
import { ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { LoginComponent } from './pages/login/login.component';
import { LoginFormComponent } from './pages/login/login-form.component';
import { CreateApplicationComponent } from './pages/applications/create-application/create-application.component';
import { ApplicationFormComponent } from './pages/applications/create-application/application-form.component';
import { JwtInterceptor } from './api/jwt-interceptor';
import { RoutesFormComponent } from './pages/applications/create-application/routes-form.component';

@NgModule({
  declarations: [
    AppComponent,
    NavbarComponent,
    HomeComponent,
    RegisterComponent,
    InputComponent,
    RegisterFormComponent,
    PageComponent,
    LoginComponent,
    LoginFormComponent,
    CreateApplicationComponent,
    ApplicationFormComponent,
    RoutesFormComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    ReactiveFormsModule,
    HttpClientModule,
  ],
  providers: [
    {
      provide: HTTP_INTERCEPTORS,
      multi: true,
      useClass: JwtInterceptor,
    },
  ],
  bootstrap: [AppComponent],
})
export class AppModule {}
