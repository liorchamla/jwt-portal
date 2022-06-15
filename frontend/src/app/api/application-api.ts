import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Subject, switchMap, tap } from 'rxjs';
import { User } from './user-api';

export type Application = {
  id?: number;
  title: string;
  description: string;
  baseUrl: string;
  routes?: any[];
  accounts: any[];
};

@Injectable({
  providedIn: 'root',
})
export class ApplicationApi {
  constructor(private http: HttpClient) {}

  applications$ = new Subject<Application[]>();

  delete(id: number) {
    return this.http
      .delete(
        'https://8000-liorchamla-jwtportal-qw83gvlw0k5.ws-eu47.gitpod.io/api/applications/' +
          id
      )
      .pipe(
        switchMap((_) => this.findAll()),
        tap((apps) => this.applications$.next(apps))
      );
  }

  update(id: number, application: Application) {
    return this.http
      .put<Application>(
        'https://8000-liorchamla-jwtportal-qw83gvlw0k5.ws-eu47.gitpod.io/api/applications/' +
          id,
        application
      )
      .pipe(
        switchMap((_) => this.findAll()),
        tap((apps) => this.applications$.next(apps))
      );
  }

  create(application: Application) {
    return this.http
      .post<Application>(
        'https://8000-liorchamla-jwtportal-qw83gvlw0k5.ws-eu47.gitpod.io/api/applications',
        application
      )
      .pipe(
        switchMap((_) => this.findAll()),
        tap((apps) => this.applications$.next(apps))
      );
  }

  findAll() {
    return this.http
      .get<Application[]>(
        'https://8000-liorchamla-jwtportal-qw83gvlw0k5.ws-eu47.gitpod.io/api/applications'
      )
      .pipe(tap((apps) => this.applications$.next(apps)));
  }

  find(id: number) {
    return this.http.get<Application>(
      'https://8000-liorchamla-jwtportal-qw83gvlw0k5.ws-eu47.gitpod.io/api/applications/' +
        id
    );
  }
}
