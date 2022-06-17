import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';

import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { ReactiveFormsModule } from '@angular/forms';
import { JwtInterceptor } from './api/jwt-interceptor';
import { InputComponent } from './components/form/input.component';
import { HomeComponent } from './features/home/home.component';
import { LayoutModule } from './layout/layout.module';

@NgModule({
  declarations: [AppComponent, HomeComponent, InputComponent],
  imports: [
    BrowserModule,
    AppRoutingModule,
    ReactiveFormsModule,
    HttpClientModule,
    LayoutModule,
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
