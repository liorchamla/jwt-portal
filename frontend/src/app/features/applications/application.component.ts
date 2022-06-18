import { HttpErrorResponse } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { catchError, filter, map, mergeMap, of, switchMap, tap } from 'rxjs';
import { Application, ApplicationApi } from 'src/app/api/application-api';
import { FormStore, FormStoreViewModel } from './form-store';

@Component({
  selector: 'app-create-application',
  template: `
    <page title="Application not found :(" *ngIf="applicationNotFound">
      <div class="box has-background-info has-text-white">
        <h2 class="is-size-2">
          The application you tried to reach does not exist.
        </h2>
        <p>Look at your top menu to find all your existing applications</p>
      </div>
    </page>
    <page
      [title]="
        application ? 'Edit my application' : 'Create a new Application !'
      "
      *ngIf="!applicationNotFound"
    >
      <div class="columns">
        <div class="column is-one-quarter">
          <application-sidebar
            [application]="application"
            (onDelete)="handleDelete()"
            (onSave)="handleForm()"
          ></application-sidebar>
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
export class ApplicationComponent implements OnInit {
  vm?: FormStoreViewModel;
  application?: Application;
  applicationNotFound = false;

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
        tap(console.log),
        map((params) => params.get('id')),
        filter((id) => id !== null),
        map((id) => +id!),
        mergeMap((id) =>
          this.applicationApi.find(id).pipe(
            catchError((err) => {
              return of(undefined);
            })
          )
        )
      )
      .subscribe({
        next: (app) => {
          if (app === undefined) {
            this.applicationNotFound = true;
            return;
          }

          this.applicationNotFound = false;
          this.application = app!;

          this.formStore.application = app!;
        },
      });

    // this.route.paramMap
    //   .pipe(
    //     map((params) => params.get('id')),
    //     filter((id) => id !== null),
    //     map((id) => +id!),
    //     mergeMap((id) => this.applicationApi.find(id))
    //     // filter((app) => {
    //     //   return app !== undefined;
    //     // })
    //   )
    //   .subscribe({
    //     next: (app) => {
    //       this.applicationNotFound = false;
    //       this.application = app!;

    //       this.formStore.application = app;
    //     },
    //     error: (error: HttpErrorResponse) => {
    //       if (error.status === 404) {
    //         this.applicationNotFound = true;
    //       }
    //     },
    //   });
  }
}
