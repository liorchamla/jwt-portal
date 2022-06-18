import {
  HttpErrorResponse,
  HttpEvent,
  HttpEventType,
  HttpHandler,
  HttpHeaders,
  HttpInterceptor,
  HttpRequest,
  HttpResponse,
} from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { Observable, tap } from 'rxjs';
import { UserApi } from './user-api';

@Injectable()
export class JwtInterceptor implements HttpInterceptor {
  constructor(private userApi: UserApi, private router: Router) {}

  intercept(
    req: HttpRequest<any>,
    next: HttpHandler
  ): Observable<HttpEvent<any>> {
    if (!this.userApi.isAuthenticated()) {
      return next.handle(req).pipe(
        tap({
          error: this.handleExpiredToken.bind(this),
        })
      );
    }

    const requestWithToken = req.clone({
      headers: new HttpHeaders({
        Authorization: 'Bearer ' + this.userApi.authToken,
      }),
    });

    return next.handle(requestWithToken).pipe(
      tap({
        error: this.handleExpiredToken.bind(this),
      })
    );
  }

  // TODO : Place all this logic inside an other Interceptor
  private handleExpiredToken(event: HttpEvent<any>) {
    if (
      event instanceof HttpErrorResponse &&
      event.status === 401 &&
      this.userApi.hasStoredToken()
    ) {
      this.userApi.logout('Session expired, please login again');
      this.router.navigateByUrl('/me/login');
    }
  }
}
