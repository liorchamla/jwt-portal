import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup } from '@angular/forms';
import { ApplicationApi } from 'src/app/api/application-api';

@Component({
  selector: 'application-form',
  template: `
    <form (ngSubmit)="handleForm()" [formGroup]="form">
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

      <button class="is-primary button">Create application</button>
    </form>
  `,
  styles: [],
})
export class ApplicationFormComponent implements OnInit {
  form = new FormGroup({
    title: new FormControl(),
    description: new FormControl(),
    baseUrl: new FormControl(),
  });

  constructor(private api: ApplicationApi) {}

  ngOnInit(): void {}

  handleForm() {
    this.api.create(this.form.value).subscribe(console.log);
  }
}
