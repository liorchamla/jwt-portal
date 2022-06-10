import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { FormArray, FormControl, FormGroup } from '@angular/forms';
import { Application, ApplicationApi } from 'src/app/api/application-api';
import { getRouteFormGroup } from './form-utils';

@Component({
  selector: 'application-form',
  template: `
    <form (ngSubmit)="formSubmit.emit()" [formGroup]="form">
      <div class="columns">
        <div class="column">
          <h2 class="is-size-3 mb-3">Application data</h2>
          <div class="field">
            <input
              class="input"
              type="text"
              placeholder="Application's title"
              name="title"
              id="title"
              formControlName="title"
            />
          </div>
          <div class="field">
            <input
              class="input"
              type="text"
              placeholder="Description"
              name="description"
              id="description"
              formControlName="description"
            />
          </div>

          <div class="field">
            <input
              class="input"
              type="text"
              placeholder="Base URL"
              name="baseUrl"
              id="baseUrl"
              formControlName="baseUrl"
            />
          </div>
          <button class="is-primary button">
            {{ buttonText }}
          </button>
        </div>
        <div class="column">
          <h2 class="is-size-3 mb-3">Application's routes</h2>
          <routes-form [formArray]="routes"></routes-form>
        </div>
      </div>
    </form>
  `,
  styles: [],
})
export class ApplicationFormComponent {
  @Input()
  application?: Application;

  @Input('formGroup')
  form!: FormGroup;

  @Input()
  buttonText = 'Create a new Application !';

  @Output()
  formSubmit = new EventEmitter();

  get routes() {
    return this.form.controls['routes'] as FormArray;
  }
}
