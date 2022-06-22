import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Subject, switchMap, tap } from 'rxjs';
import { environment } from 'src/environments/environment';
import { User } from './user-api';

const API_URL = environment.apiUrl;

export type Application = {
  id?: number;
  slug?: string;
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

  delete(slug: string) {
    return this.http.delete(API_URL + '/api/applications/' + slug).pipe(
      switchMap((_) => this.findAll()),
      tap((apps) => this.applications$.next(apps))
    );
  }

  update(slug: string, application: Application) {
    return this.http
      .put<Application>(API_URL + '/api/applications/' + slug, application)
      .pipe(
        switchMap((_) => this.findAll()),
        tap((apps) => this.applications$.next(apps))
      );
  }

  create(application: Application) {
    return this.http
      .post<Application>(API_URL + '/api/applications', application)
      .pipe(
        switchMap((_) => this.findAll()),
        tap((apps) => this.applications$.next(apps))
      );
  }

  findAll() {
    return this.http
      .get<Application[]>(API_URL + '/api/applications')
      .pipe(tap((apps) => this.applications$.next(apps)));
  }

  find(slug: string) {
    return this.http.get<Application>(API_URL + '/api/applications/' + slug);
  }
}
