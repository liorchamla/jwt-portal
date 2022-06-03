import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-create-application',
  template: `
    <page title="Create a new Application !">
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
          <application-form></application-form>
        </div>
      </div>
    </page>
  `,
  styles: [],
})
export class CreateApplicationComponent implements OnInit {
  constructor() {}

  ngOnInit(): void {}
}
