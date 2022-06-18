import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { switchMap, tap } from 'rxjs';
import jwtDecode from 'jwt-decode';
import { Application, ApplicationApi } from './application-api';
import { environment } from 'src/environments/environment';

const API_URL = environment.apiUrl;

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

  constructor(
    private http: HttpClient,
    private applicationApi: ApplicationApi
  ) {}

  getUserData() {
    if (!this.hasStoredToken()) {
      return null;
    }

    return jwtDecode(this.authToken!) as User;
  }

  hasStoredToken() {
    const token = window.localStorage.getItem('token');

    if (token === null) {
      return false;
    }

    this.authToken = token;

    return true;
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

  logout(message: string = '') {
    window.localStorage.removeItem('token');

    this.authToken = undefined;

    if (message) {
      window.alert(message);
    }
  }

  register(registration: Registration) {
    return this.http.post(API_URL + '/api/register', registration);
  }

  login(credentials: Credentials) {
    return this.http
      .post<{ token: string }>(API_URL + '/api/login', credentials)
      .pipe(
        tap((data: { token: string }) => {
          this.authToken = data.token;
          window.localStorage.setItem('token', data.token);
        }),
        switchMap((_) => this.applicationApi.findAll())
      );
  }
}
