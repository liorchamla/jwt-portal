import { FormArray, FormControl, FormGroup, Validators } from '@angular/forms';
import { BehaviorSubject, Subject } from 'rxjs';
import { Application } from 'src/app/api/application-api';
import { getRouteFormGroup } from './form-utils';

export type FormStoreViewModel = {
  formGroup: FormGroup;
  routes: FormArray;
  application?: Application;
  accounts?: any[];
};

export class FormStore {
  viewModel$!: BehaviorSubject<FormStoreViewModel>;
  private _form!: FormGroup;
  private _application?: Application;

  constructor() {
    this._form = new FormGroup({
      title: new FormControl('', Validators.required),
      description: new FormControl('', Validators.required),
      routes: new FormArray([]),
    });

    this.viewModel$ = new BehaviorSubject<FormStoreViewModel>({
      formGroup: this._form,
      routes: this.routes,
    });
  }

  set application(application: Application) {
    this._application = application;

    this._form.patchValue(application);

    this.routes.clear();

    application.routes?.forEach((route) =>
      this.routes.push(getRouteFormGroup(route))
    );

    this.viewModel$.next({
      application: application,
      formGroup: this._form,
      routes: this.routes,
      accounts: application.accounts,
    });
  }

  get routes() {
    return this._form.controls['routes'] as FormArray;
  }
}
