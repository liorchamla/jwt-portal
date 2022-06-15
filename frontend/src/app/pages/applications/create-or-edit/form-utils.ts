import { FormControl, FormGroup, Validators } from '@angular/forms';

export function getRouteFormGroup(
  route: any = { pattern: '', clientPattern: '' }
): FormGroup {
  return new FormGroup({
    pattern: new FormControl(route.pattern, [Validators.required]),
    clientPattern: new FormControl(route.clientPattern, Validators.required),
    description: new FormControl(route.description, Validators.required),
    isProtected: new FormControl(route.isProtected),
    method: new FormControl(route.method),
  });
}
