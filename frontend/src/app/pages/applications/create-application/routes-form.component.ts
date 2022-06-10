import { Component, Input, OnInit } from '@angular/core';
import { FormArray, FormControl, FormGroup } from '@angular/forms';
import { getRouteFormGroup } from './form-utils';

@Component({
  selector: 'routes-form',
  template: `
    <p
      class="box has-text-white has-background-info"
      *ngIf="groups.length === 0"
    >
      You should add API routes
    </p>
    <ng-container
      *ngFor="let group of groups; let i = index"
      [formGroup]="group"
    >
      <div class="field">
        <input
          type="text"
          class="input"
          placeholder="/endpoint/{param1}/{param2}"
          id="pattern"
          name="pattern"
          formControlName="pattern"
        />
      </div>
      <div class="field">
        <input
          type="text"
          class="input"
          placeholder="/custom/{param2}/and/{param1}"
          id="clientPattern"
          name="clientPattern"
          formControlName="clientPattern"
        />
      </div>
      <button
        class="is-danger button"
        type="button"
        (click)="formArray.removeAt(i)"
      >
        Remove
      </button>
      <hr />
    </ng-container>
    <button
      class="button is-outlined is-primary"
      type="button"
      (click)="addGroup()"
    >
      Add a route
    </button>
  `,
  styles: [],
})
export class RoutesFormComponent implements OnInit {
  @Input()
  formArray!: FormArray;

  get groups() {
    return this.formArray.controls as FormGroup[];
  }

  addGroup() {
    this.formArray.push(getRouteFormGroup());
  }

  constructor() {}

  ngOnInit(): void {}
}
