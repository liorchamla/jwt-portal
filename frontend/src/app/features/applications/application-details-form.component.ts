import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { FormArray, FormControl, FormGroup } from '@angular/forms';
import { Application, ApplicationApi } from 'src/app/api/application-api';
import { FormStore, FormStoreViewModel } from './form-store';
import { getRouteFormGroup } from './form-utils';

@Component({
  selector: 'application-details-form',
  template: `
    <ng-container *ngIf="vm" [formGroup]="vm.formGroup">
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
    </ng-container>
  `,
  styles: [],
})
export class ApplicationDetailsFormComponent {
  vm?: FormStoreViewModel;

  constructor(private formStore: FormStore) {}

  ngOnInit() {
    this.formStore.viewModel$.subscribe((vm) => (this.vm = vm));
  }

  handleDelete() {}
}
