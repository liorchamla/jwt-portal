import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { ReactiveFormsModule } from '@angular/forms';
import { LayoutModule } from 'src/app/layout/layout.module';
import { ApplicationAccountsComponent } from './application-accounts.component';
import { ApplicationDetailsFormComponent } from './application-details-form.component';
import { ApplicationRoutesFormComponent } from './application-routes-form.component';
import { ApplicationRoutingModule } from './application-routing.module';
import { ApplicationSidebarComponent } from './application-sidebar.component';
import { ApplicationComponent } from './application.component';

@NgModule({
  imports: [
    CommonModule,
    ReactiveFormsModule,
    LayoutModule,
    ApplicationRoutingModule,
  ],
  declarations: [
    ApplicationComponent,
    ApplicationDetailsFormComponent,
    ApplicationRoutesFormComponent,
    ApplicationAccountsComponent,
    ApplicationSidebarComponent,
  ],
})
export class ApplicationModule {}
