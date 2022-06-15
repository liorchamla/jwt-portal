import { Component } from '@angular/core';
import { Observable } from 'rxjs';
import { Application, ApplicationApi } from '../api/application-api';
import { UserApi } from '../api/user-api';

@Component({
  selector: 'navigation',
  template: `
    <nav class="navbar" role="navigation" aria-label="main navigation">
      <div class="navbar-brand">
        <a class="navbar-item" href="/">
          <strong>✨ AuthPortal</strong>
        </a>

        <a
          role="button"
          class="navbar-burger"
          aria-label="menu"
          aria-expanded="false"
          data-target="navbarBasicExample"
        >
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>

      <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">
          <div
            class="navbar-item has-dropdown is-hoverable"
            *ngIf="getUser() as user"
          >
            <a class="navbar-link">
              <span class="icon">
                <i class="fa-solid fa-book"></i>
              </span>
              <span> My Applications </span>
            </a>

            <div class="navbar-dropdown">
              <a
                class="navbar-item"
                [routerLink]="['/applications', app.id]"
                *ngFor="let app of applications"
              >
                {{ app.title }}
              </a>
              <hr class="navbar-divider" *ngIf="applications.length > 0" />
              <a class="navbar-item" routerLink="/applications/create">
                Create a new application
              </a>
            </div>
          </div>
        </div>

        <div class="navbar-end">
          <div class="navbar-item">
            <div class="buttons">
              <ng-container *ngIf="!isAuthenticated()">
                <a class="button is-primary" routerLink="/register">
                  <span class="icon">
                    <i class="fa-solid fa-user-plus"></i>
                  </span>
                  <span>Sign up</span>
                </a>
                <a class="button is-light" routerLink="/login">
                  <span class="icon">
                    <i class="fa-solid fa-right-to-bracket"></i>
                  </span>
                  <span>Sign in</span>
                </a>
              </ng-container>
              <a
                class="button is-warning"
                *ngIf="isAuthenticated()"
                (click)="handleLogout()"
              >
                <span class="icon">
                  <i class="fa-solid fa-right-from-bracket"></i>
                </span>
                <span>Log out</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </nav>
  `,
})
export class NavbarComponent {
  applications: Application[] = [];

  constructor(
    private userApi: UserApi,
    private applicationApi: ApplicationApi
  ) {}

  ngOnInit() {
    this.applicationApi.applications$.subscribe(
      (apps) => (this.applications = apps)
    );

    this.applicationApi
      .findAll()
      .subscribe((apps) => (this.applications = apps));
  }

  getUser() {
    return this.userApi.getUserData();
  }

  isAuthenticated() {
    return this.userApi.isAuthenticated();
  }

  handleLogout() {
    this.userApi.logout();
  }
}
