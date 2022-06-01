import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  template: `
    <navigation></navigation>
    <router-outlet></router-outlet>
  `,
})
export class AppComponent {
  title = 'frontend';
}
