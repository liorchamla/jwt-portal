import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

export type Registration = {
  email: string;
  password: string;
};

@Injectable({
  providedIn: 'root',
})
export class UserApi {
  constructor(private http: HttpClient) {}

  register(registration: Registration) {
    return this.http.post(
      'https://8000-liorchamla-jwtportal-qw83gvlw0k5.ws-eu46.gitpod.io/api/register',
      registration
    );
  }
}
