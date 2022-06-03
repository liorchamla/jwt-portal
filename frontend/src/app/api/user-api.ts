import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { tap } from 'rxjs';
import jwtDecode from 'jwt-decode';

export type User = {
  username: string;
  applications: any[];
  roles: string[];
};

export type Registration = {
  email: string;
  password: string;
};

export type Credentials = Registration;

@Injectable({
  providedIn: 'root',
})
export class UserApi {
  authToken?: string;

  constructor(private http: HttpClient) {}

  getUserData() {
    if (!this.authToken) {
      return null;
    }

    return jwtDecode(this.authToken) as User;
  }

  isAuthenticated() {
    if (this.authToken) {
      return true;
    }

    const token = window.localStorage.getItem('token') || null;

    if (!token) {
      return false;
    }

    this.authToken = token;
    return true;
  }

  logout() {
    window.localStorage.removeItem('token');

    this.authToken = undefined;
  }

  register(registration: Registration) {
    return this.http.post(
      'https://8000-liorchamla-jwtportal-qw83gvlw0k5.ws-eu46.gitpod.io/api/register',
      registration
    );
  }

  login(credentials: Credentials) {
    return this.http
      .post<{ token: string }>(
        'https://8000-liorchamla-jwtportal-qw83gvlw0k5.ws-eu46.gitpod.io/api/login',
        credentials
      )
      .pipe(
        tap((data: { token: string }) => {
          this.authToken = data.token;
          window.localStorage.setItem('token', data.token);
        })
      );
  }
}
