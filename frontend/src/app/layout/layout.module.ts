import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { NavbarComponent } from './navbar.component';
import { PageComponent } from './page.component';

@NgModule({
  imports: [CommonModule, RouterModule],
  exports: [NavbarComponent, PageComponent],
  declarations: [NavbarComponent, PageComponent],
})
export class LayoutModule {}
