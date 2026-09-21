import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { AvisoDetalleComponent } from './aviso-detalle/aviso-detalle.component';
import { AvisoEditarComponent } from './aviso-editar/aviso-editar.component';
import { sesionGuard } from './guardias/sesion.guard';

const routes: Routes = [
  { path: '', pathMatch: 'full', children: [] },
  { path: 'avisos/:id/editar', component: AvisoEditarComponent, canActivate: [sesionGuard] },
  { path: 'avisos/:id', component: AvisoDetalleComponent },
  { path: '**', redirectTo: '' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
