import { Component } from '@angular/core';

@Component({
  selector: 'navigation',
  template: `
    <nav class="navbar" role="navigation" aria-label="main navigation">
      <div class="navbar-brand">
        <a class="navbar-item" href="/">
          <strong>✨ AuthPortal</strong>
        </a>

        <a
          role="button"
          class="navbar-burger"
          aria-label="menu"
          aria-expanded="false"
          data-target="navbarBasicExample"
        >
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>

      <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">
          <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">
              <span class="icon">
                <i class="fa-solid fa-book"></i>
              </span>
              <span> My Applications </span>
            </a>

            <div class="navbar-dropdown">
              <a class="navbar-item"> Application X </a>
              <a class="navbar-item"> Application Y </a>
              <hr class="navbar-divider" />
              <a class="navbar-item"> Create a new application </a>
            </div>
          </div>
        </div>

        <div class="navbar-end">
          <div class="navbar-item">
            <div class="buttons">
              <a class="button is-primary">
                <span class="icon">
                  <i class="fa-solid fa-user-plus"></i>
                </span>
                <span>Sign up</span>
              </a>
              <a class="button is-light">
                <span class="icon">
                  <i class="fa-solid fa-right-to-bracket"></i>
                </span>
                <span>Sign in</span>
              </a>
              <a class="button is-warning">
                <span class="icon">
                  <i class="fa-solid fa-right-from-bracket"></i>
                </span>
                <span>Log out</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </nav>
  `,
})
export class NavbarComponent {}
