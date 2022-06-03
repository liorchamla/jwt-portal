import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup } from '@angular/forms';
import { Router } from '@angular/router';
import { UserApi } from 'src/app/api/user-api';

@Component({
  selector: 'login-form',
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
        <div class="control">
          <button class="button is-primary">Log in !</button>
        </div>
      </div>
    </form>
  `,
  styles: [],
})
export class LoginFormComponent implements OnInit {
  form = new FormGroup({
    email: new FormControl(),
    password: new FormControl(),
  });

  constructor(private userApi: UserApi, private router: Router) {}

  ngOnInit(): void {}

  handleSubmit() {
    this.userApi.login(this.form.value).subscribe({
      next: () => this.router.navigateByUrl('/'),
      error: () => window.alert('Non !'),
    });
  }
}
