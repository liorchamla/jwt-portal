import { Component, OnInit } from '@angular/core';
import { FormArray, FormControl, FormGroup } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { filter, map, switchMap } from 'rxjs';
import { Application, ApplicationApi } from 'src/app/api/application-api';
import { FormStore, FormStoreViewModel } from './form-store';
import { getRouteFormGroup } from './form-utils';

@Component({
  selector: 'app-create-application',
  template: `
    <page
      [title]="
        application ? 'Edit my application' : 'Create a new Application !'
      "
    >
      <div class="columns">
        <div class="column is-one-quarter">
          <aside class="menu">
            <p class="menu-label">Manage this application</p>
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
                  >General informations</a
                >
              </li>
              <li>
                <a [routerLink]="['routes']" routerLinkActive="is-active"
                  >Routes management</a
                >
              </li>
              <li>
                <a [routerLink]="['accounts']" routerLinkActive="is-active"
                  >Accounts</a
                >
              </li>
            </ul>

            <p class="menu-label">Actions</p>
            <ul class="menu-list">
              <li>
                <button
                  class="button is-fullwidth is-primary"
                  (click)="handleForm()"
                >
                  Save
                </button>
              </li>
              <li>
                <button class="button is-fullwidth is-danger mt-2 is-outlined">
                  Delete Application
                </button>
              </li>
            </ul>
          </aside>
        </div>
        <div class="column">
          <div
            class="box has-background-danger has-text-white"
            *ngIf="vm && vm.formGroup.invalid"
          >
            Votre formulaire possède des erreurs !
          </div>
          <router-outlet></router-outlet>
        </div>
      </div>
    </page>
  `,
  styles: [],
  providers: [FormStore],
})
export class CreateOrEditApplicationComponent implements OnInit {
  vm?: FormStoreViewModel;
  application?: Application;

  handleDelete() {
    if (!this.application) {
      return;
    }

    if (!window.confirm('Are you sure ?')) {
      return;
    }

    this.applicationApi
      .delete(this.application.id!)
      .subscribe((_) => this.router.navigateByUrl('/'));
  }

  get routes() {
    return this.formStore.routes;
  }

  constructor(
    private applicationApi: ApplicationApi,
    private route: ActivatedRoute,
    private router: Router,
    private formStore: FormStore
  ) {}

  handleForm() {
    if (this.application) {
      this.applicationApi
        .update(this.application.id!, this.vm?.formGroup.value)
        .subscribe(console.log);
      return;
    }

    this.applicationApi.create(this.vm?.formGroup.value).subscribe(console.log);
  }

  ngOnInit(): void {
    this.formStore.viewModel$.subscribe((vm) => {
      this.vm = vm;
      this.application = vm.application;
    });

    this.route.paramMap
      .pipe(
        map((params) => params.get('id')),
        filter((id) => id !== null),
        map((id) => +id!),
        switchMap((id) => this.applicationApi.find(id)),
        filter((app) => {
          return app !== undefined;
        })
      )
      .subscribe((app) => {
        this.application = app!;

        this.formStore.application = app;
      });
  }
}
