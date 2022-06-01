import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-home',
  template: `
    <section class="hero is-fullheight is-link">
      <div class="hero-body">
        <div class="container has-text-centered">
          <p class="title">Welcome to AuthPortal !</p>
          <p class="subtitle">
            Create an authentication layer in front of any public API !
          </p>
          <button class="button is-primary is-large is-outlined">
            <span class="icon">
              <i class="fa-solid fa-user-plus"></i>
            </span>
            <span>Get started : sign up !</span>
          </button>
        </div>
      </div>
    </section>
  `,
  styles: [],
})
export class HomeComponent implements OnInit {
  constructor() {}

  ngOnInit(): void {}
}
