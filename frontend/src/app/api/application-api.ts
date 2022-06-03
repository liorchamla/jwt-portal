import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

export type Application = {
  id?: number;
  title: string;
  description: string;
  baseUrl: string;
  routes?: any[];
};

@Injectable({
  providedIn: 'root',
})
export class ApplicationApi {
  constructor(private http: HttpClient) {}

  create(application: Application) {
    return this.http.post(
      'https://8000-liorchamla-jwtportal-qw83gvlw0k5.ws-eu46.gitpod.io/api/applications',
      application
    );
  }
}
