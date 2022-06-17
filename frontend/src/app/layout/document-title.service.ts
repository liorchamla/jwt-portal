import { Injectable } from '@angular/core';
import { ActivationEnd, Event, Router } from '@angular/router';
import { distinctUntilChanged, filter, map } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class DocumentTitle {
  constructor(router: Router) {
    router.events
      .pipe(
        filter((event: Event) => event instanceof ActivationEnd),
        map((event: any) => event.snapshot.data['title'] as string),
        filter((title) => title !== undefined),
        distinctUntilChanged()
      )
      .subscribe((title) => {
        document.title = 'ApiAuthProxy - ' + title;
      });
  }
}
