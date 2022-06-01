import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'bu-input',
  template: `
    <div class="field">
      <label class="label">Name</label>
      <div class="control">
        <input class="input" type="text" placeholder="Text input" />
      </div>
    </div>
  `,
  styles: [],
})
export class InputComponent implements OnInit {
  constructor() {}

  ngOnInit(): void {}
}
