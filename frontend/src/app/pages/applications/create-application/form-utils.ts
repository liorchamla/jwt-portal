import { FormControl, FormGroup } from '@angular/forms';

export function getRouteFormGroup(
  route: any = { pattern: '', clientPattern: '' }
): FormGroup {
  return new FormGroup({
    pattern: new FormControl(route.pattern),
    clientPattern: new FormControl(route.clientPattern),
  });
}
