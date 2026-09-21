import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';

import { SesionService } from '../servicios/sesion.service';

export const sesionGuard: CanActivateFn = () =>
  inject(SesionService).token ? true : inject(Router).parseUrl('/');