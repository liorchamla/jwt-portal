import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { ReactiveFormsModule } from '@angular/forms';
import { LayoutModule } from 'src/app/layout/layout.module';
import { LoginFormComponent } from './login/login-form.component';
import { LoginComponent } from './login/login.component';
import { RegisterFormComponent } from './register/register-form.component';
import { RegisterComponent } from './register/register.component';
import { UsersRoutingModule } from './users-routing.module';

@NgModule({
  imports: [
    CommonModule,
    ReactiveFormsModule,
    UsersRoutingModule,
    LayoutModule,
  ],
  declarations: [
    LoginComponent,
    LoginFormComponent,
    RegisterComponent,
    RegisterFormComponent,
  ],
})
export class UsersModule {}
