import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'login',
  template: `
    <page title="Log in !">
      <div class="columns">
        <div class="column">
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sit,
            deserunt.
          </p>
          <p>
            Excepturi suscipit nesciunt iste ut repellat illo asperiores enim
            nam.
          </p>
          <p>
            Reiciendis aliquam totam atque cumque explicabo iste, harum veniam
            perferendis.
          </p>
        </div>
        <div class="column">
          <login-form></login-form>
        </div>
      </div>
    </page>
  `,
  styles: [],
})
export class LoginComponent implements OnInit {
  constructor() {}

  ngOnInit(): void {}
}
