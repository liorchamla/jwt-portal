import {
  HttpEvent,
  HttpHandler,
  HttpHeaders,
  HttpInterceptor,
  HttpRequest,
} from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { UserApi } from './user-api';

@Injectable()
export class JwtInterceptor implements HttpInterceptor {
  constructor(private userApi: UserApi) {}

  intercept(
    req: HttpRequest<any>,
    next: HttpHandler
  ): Observable<HttpEvent<any>> {
    if (!this.userApi.isAuthenticated()) {
      return next.handle(req);
    }

    const requestWithToken = req.clone({
      headers: new HttpHeaders({
        Authorization: 'Bearer ' + this.userApi.authToken,
      }),
    });

    return next.handle(requestWithToken);
  }
}
