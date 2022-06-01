import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup } from '@angular/forms';
import { UserApi } from 'src/app/api/user-api';

@Component({
  selector: 'register-form',
  template: `
    <form class="box" [formGroup]="form" (ngSubmit)="handleSubmit()">
      <div class="field">
        <label for="email" class="label">Email address</label>
        <div class="control">
          <input
            type="email"
            class="input"
            placeholder="john@doe.com"
            id="email"
            name="email"
            formControlName="email"
          />
        </div>
      </div>
      <div class="field">
        <label for="password" class="label">Password</label>
        <div class="control">
          <input
            type="text"
            class="input"
            placeholder="h4rdc0re p4ssw0rd !"
            id="password"
            name="password"
            formControlName="password"
          />
        </div>
      </div>
      <div class="field">
        <label for="password-confirm" class="label">Confirm password</label>
        <div class="control">
          <input
            type="text"
            class="input"
            placeholder="Confirm your password"
            id="password-confirm"
            name="password-confirm"
            formControlName="passwordConfirm"
          />
        </div>
      </div>
      <div class="field">
        <div class="control">
          <button class="button is-primary">Register now !</button>
        </div>
      </div>
    </form>
  `,
  styles: [],
})
export class RegisterFormComponent implements OnInit {
  form = new FormGroup({
    email: new FormControl(),
    password: new FormControl(),
    passwordConfirm: new FormControl(),
  });

  constructor(private userApi: UserApi) {}

  ngOnInit(): void {}

  handleSubmit() {
    this.userApi.register(this.form.value).subscribe(console.log);
  }
}
