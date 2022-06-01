import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-register',
  template: `
    <page title="Register and get started now!">
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
          <register-form></register-form>
        </div>
      </div>
    </page>
  `,
  styles: [],
})
export class RegisterComponent implements OnInit {
  constructor() {}

  ngOnInit(): void {}
}
