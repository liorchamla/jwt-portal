import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { tap } from 'rxjs';
import { User } from './user-api';

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

  update(id: number, application: Application) {
    return this.http
      .put<Application>(
        'https://8000-liorchamla-jwtportal-qw83gvlw0k5.ws-eu47.gitpod.io/api/applications/' +
          id,
        application
      )
      .pipe(tap((app) => this.synchronizeForNewApplication(app)));
  }

  create(application: Application) {
    return this.http
      .post<Application>(
        'https://8000-liorchamla-jwtportal-qw83gvlw0k5.ws-eu47.gitpod.io/api/applications',
        application
      )
      .pipe(tap((app) => this.synchronizeForNewApplication(app)));
  }

  findAll() {
    return this.http
      .get<Application[]>(
        'https://8000-liorchamla-jwtportal-qw83gvlw0k5.ws-eu47.gitpod.io/api/applications'
      )
      .pipe(tap((apps) => this.saveApplicationsCache(apps)));
  }

  saveApplicationsCache(applications: Application[]) {
    window.localStorage.setItem('applications', JSON.stringify(applications));
  }

  getApplicationsData(): Application[] {
    return JSON.parse(window.localStorage.getItem('applications') || '[]');
  }

  synchronizeForNewApplication(application: Application) {
    const alreadyCachedApplications = this.getApplicationsData();
    console.log(application);

    const hasThisApplicationAlready = alreadyCachedApplications.some(
      (a) => a.id === application.id
    );

    if (hasThisApplicationAlready) {
      const index = alreadyCachedApplications.findIndex(
        (a) => a.id === application.id
      );
      alreadyCachedApplications[index] = application;
    } else {
      alreadyCachedApplications.push(application);
    }

    this.saveApplicationsCache(alreadyCachedApplications);
  }

  clearCache() {
    window.localStorage.removeItem('applications');
  }
}
