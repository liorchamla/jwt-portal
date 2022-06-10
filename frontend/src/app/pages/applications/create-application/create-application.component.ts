import { Component, OnInit } from '@angular/core';
import { FormArray, FormControl, FormGroup } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { filter, map, switchMap } from 'rxjs';
import { Application, ApplicationApi } from 'src/app/api/application-api';
import { getRouteFormGroup } from './form-utils';

@Component({
  selector: 'app-create-application',
  template: `
    <page
      [title]="
        application ? 'Edit my application' : 'Create a new Application !'
      "
    >
      <div class="column">
        <application-form
          [buttonText]="
            application !== undefined
              ? 'Save modifications'
              : 'Create a new application !'
          "
          [formGroup]="form"
          (formSubmit)="handleForm()"
        ></application-form>
      </div>
    </page>
  `,
  styles: [],
})
export class CreateApplicationComponent implements OnInit {
  application?: Application;

  form = new FormGroup({
    title: new FormControl(),
    description: new FormControl(),
    baseUrl: new FormControl(),
    routes: new FormArray([]),
  });

  get routes() {
    return this.form.controls['routes'] as FormArray;
  }

  constructor(
    private applicationApi: ApplicationApi,
    private route: ActivatedRoute
  ) {}

  handleForm() {
    if (this.application) {
      this.applicationApi
        .update(this.application.id!, this.form.value)
        .subscribe(console.log);
      return;
    }

    this.applicationApi.create(this.form.value).subscribe(console.log);
  }

  ngOnInit(): void {
    this.route.paramMap
      .pipe(
        map((params) => params.get('id')),
        filter((id) => id !== null),
        map((id) => +id!),
        map((id) =>
          this.applicationApi.getApplicationsData().find((a) => a.id === id)
        )
      )
      .subscribe((app) => {
        this.application = app!;

        this.form.patchValue(this.application);

        this.form.controls['routes'];

        this.routes.clear();

        this.application.routes?.forEach((route) =>
          this.routes.push(getRouteFormGroup(route))
        );
      });
  }
}
