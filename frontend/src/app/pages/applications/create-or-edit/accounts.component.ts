import { Component, OnInit } from '@angular/core';
import { FormStore, FormStoreViewModel } from './form-store';

@Component({
  selector: 'app-accounts',
  template: `
    <h2 class="is-size-3">Application's accounts</h2>
    <table class="table is-fullwidth">
      <thead>
        <tr>
          <th>Id</th>
          <th>Email</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr *ngFor="let a of vm.application?.accounts">
          <td>{{ a.id }}</td>
          <td>{{ a.email }}</td>
          <td></td>
        </tr>
      </tbody>
    </table>
  `,
  styles: [],
})
export class AccountsComponent implements OnInit {
  vm!: FormStoreViewModel;

  constructor(private formStore: FormStore) {}

  ngOnInit(): void {
    this.formStore.viewModel$.subscribe((vm) => (this.vm = vm));
  }
}
