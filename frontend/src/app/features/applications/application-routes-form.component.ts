import { Component, OnInit } from '@angular/core';
import { FormGroup } from '@angular/forms';
import { FormStore, FormStoreViewModel } from './form-store';
import { getRouteFormGroup } from './form-utils';

@Component({
  selector: 'application-routes-form',
  template: `
    <h2 class="is-size-3 mb-2">Manage routes</h2>
    <div
      class="box has-text-white has-background-info"
      *ngIf="groups.length === 0"
    >
      <p>You should add API routes</p>
      <button class="mt-3 button is-medium is-primary" (click)="addGroup()">
        Add your first Route !
      </button>
    </div>
    <div
      class="box"
      *ngFor="let group of groups; let i = index"
      [formGroup]="group"
    >
      <div class="field is-horizontal">
        <div class="field-label is-normal">
          <label for="pattern_{{ i }}" class="label">Real API URL</label>
        </div>
        <div class="field-body">
          <div class="field has-addons">
            <p class="control">
              <span class="select">
                <select
                  id="method_{{ i }}"
                  name="method_{{ i }}"
                  formControlName="method"
                >
                  <option value="GET">GET</option>
                  <option value="POST">POST</option>
                  <option value="PUT">PUT</option>
                  <option value="PATCH">PATCH</option>
                  <option value="DELETE">DELETE</option>
                </select>
              </span>
            </p>
            <p class="control is-expanded">
              <input
                type="text"
                class="input"
                placeholder="https://your-api.io/your-endpoint/{param1}?param={param2}"
                id="pattern_{{ i }}"
                name="pattern_{{ i }}"
                formControlName="pattern"
              />
            </p>
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label is-normal">
          <label for="pattern_{{ i }}" class="label">New API Route</label>
        </div>
        <div class="field-body">
          <div class="field">
            <input
              type="text"
              class="input"
              placeholder="/custom/{param2}/and/{param1}"
              id="clientPattern_{{ i }}"
              name="clientPattern_{{ i }}"
              formControlName="clientPattern"
            />
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label is-normal">
          <label for="pattern_{{ i }}" class="label">Description</label>
        </div>
        <div class="field-body">
          <div class="field">
            <input
              type="text"
              class="input"
              placeholder="Describe this API route and what it does !"
              id="description_{{ i }}"
              name="description_{{ i }}"
              formControlName="description"
            />
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label is-normal">
          <label for="pattern_{{ i }}" class="label">Authentication</label>
        </div>
        <div class="field-body">
          <div class="field">
            <label for="isProtected_{{ i }}" class="checkbox">
              <input
                type="checkbox"
                class="mr-2"
                id="isProtected_{{ i }}"
                name="isProtected_{{ i }}"
                formControlName="isProtected"
                #protected
              />
              <span *ngIf="protected.checked">
                This route <strong>is protected</strong> by authentication
                <i class="fa fa-lock"></i
              ></span>
              <span *ngIf="!protected.checked">
                This route <strong>is not protected</strong> by authentication
                <i class="fa fa-lock-open"></i
              ></span>
            </label>
          </div>
        </div>
      </div>
      <button
        class="is-danger button"
        type="button"
        (click)="vm.routes.removeAt(i)"
      >
        Remove
      </button>
    </div>
    <button
      *ngIf="vm.routes.length > 0"
      class="button is-outlined is-primary"
      type="button"
      (click)="addGroup()"
    >
      Add a route
    </button>
  `,
  styles: [],
})
export class ApplicationRoutesFormComponent implements OnInit {
  vm!: FormStoreViewModel;

  constructor(private formStore: FormStore) {}

  get groups() {
    return this.vm.routes.controls as FormGroup[];
  }

  addGroup() {
    this.vm.routes.push(getRouteFormGroup());
  }

  ngOnInit(): void {
    this.formStore.viewModel$.subscribe((vm) => (this.vm = vm));
  }
}
