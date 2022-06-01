import { Component, Input, OnInit } from '@angular/core';

@Component({
  selector: 'page',
  template: `
    <ng-container *ngIf="title">
      <div class="hero is-{{ heroColor || 'primary' }}">
        <div class="hero-body container is-max-desktop">
          <div class="title">
            <h1 class="is-size-1">{{ title }}</h1>
          </div>
        </div>
      </div>
    </ng-container>
    <div class="container is-max-desktop mt-5">
      <ng-content></ng-content>
    </div>
  `,
  styles: [],
})
export class PageComponent {
  @Input()
  title?: string;

  @Input('hero-color')
  heroColor?: string;
}
