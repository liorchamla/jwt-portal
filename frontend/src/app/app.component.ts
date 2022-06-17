import { Component } from '@angular/core';
import { DocumentTitle } from './layout/document-title.service';

@Component({
  selector: 'app-root',
  template: `
    <navigation></navigation>
    <router-outlet></router-outlet>
  `,
})
export class AppComponent {
  constructor(documentTitle: DocumentTitle) {}
}
