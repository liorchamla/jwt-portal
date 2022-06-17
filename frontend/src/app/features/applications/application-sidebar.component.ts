import { Component, EventEmitter, Input, Output } from '@angular/core';
import { Application } from 'src/app/api/application-api';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'application-sidebar',
  template: ` <aside class="menu">
    <p class="menu-label">
      <i class="fa-solid fa-cog"></i> Manage this application
    </p>
    <ul class="menu-list">
      <li>
        <a
          [routerLink]="[
            application !== undefined
              ? '/applications/' + application.id
              : '/applications/create'
          ]"
          routerLinkActive="is-active"
          [routerLinkActiveOptions]="{ exact: true }"
          ><i class="fa-solid fa-circle-info"></i> General informations</a
        >
      </li>
      <li>
        <a [routerLink]="['routes']" routerLinkActive="is-active"
          ><i class="fa-solid fa-route"></i> Routes management</a
        >
      </li>
      <li>
        <a [routerLink]="['accounts']" routerLinkActive="is-active"
          ><i class="fa-solid fa-users"></i> Accounts</a
        >
      </li>
      <li *ngIf="application">
        <a [href]="apiUrl + '/swagger/' + application.id" target="_blank"
          ><i class="fa-solid fa-book"></i> API Documentation</a
        >
      </li>
    </ul>

    <p class="menu-label"><i class="fa-solid fa-bolt-lightning"></i> Actions</p>
    <ul class="menu-list">
      <li>
        <button class="button is-fullwidth is-primary" (click)="onSave.emit()">
          <span class="icon">
            <i class="fa-solid fa-floppy-disk"></i>
          </span>
          <span> Save </span>
        </button>
      </li>
      <li>
        <button
          (click)="onDelete.emit()"
          class="button is-fullwidth is-danger mt-2 is-outlined"
        >
          <span class="icon">
            <i class="fa-solid fa-trash-can"></i>
          </span>
          <span> Delete Application</span>
        </button>
      </li>
    </ul>
  </aside>`,
})
export class ApplicationSidebarComponent {
  apiUrl = environment.apiUrl;

  @Input()
  application?: Application;

  @Output()
  onDelete = new EventEmitter();

  @Output()
  onSave = new EventEmitter();
}
