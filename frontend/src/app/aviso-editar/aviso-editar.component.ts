import { Component, OnInit, ViewChild } from '@angular/core';
import { HttpErrorResponse } from '@angular/common/http';
import { NgForm } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';

import { Aviso, NuevoAviso } from '../modelos/aviso';
import { AvisosService } from '../servicios/avisos.service';

@Component({
  selector: 'app-aviso-editar',
  templateUrl: './aviso-editar.component.html',
  styleUrls: ['./aviso-editar.component.css']
})
export class AvisoEditarComponent implements OnInit {
  @ViewChild('form') form!: NgForm;

  aviso: NuevoAviso = { titulo: '', contenido: '', categoria_id: null };
  id = 0;
  cargando = true;
  guardando = false;
  errores: Record<string, string[]> = {};
  mensaje = '';

  constructor(
    private ruta: ActivatedRoute,
    private router: Router,
    private avisosService: AvisosService
  ) { }

  ngOnInit(): void {
    this.id = Number(this.ruta.snapshot.paramMap.get('id'));
    this.avisosService.uno(this.id).subscribe({
      next: (aviso: Aviso) => {
        this.form.setValue({
          titulo: aviso.titulo,
          contenido: aviso.contenido,
          categoria_id: aviso.categoria?.id ?? null
        });
        this.cargando = false;
      },
      error: () => {
        this.cargando = false;
        this.mensaje = '404 · ese aviso no existe';
      }
    });
  }

  guardar(): void {
    this.errores = {};
    this.mensaje = '';
    this.guardando = true;

    this.avisosService.actualizar(this.id, this.aviso).subscribe({
      next: () => {
        this.guardando = false;
        this.router.navigate(['/avisos', this.id]);
      },
      error: (e: HttpErrorResponse) => {
        this.guardando = false;
        if (e.status === 403) {
          this.mensaje = '403 · ese aviso no es tuyo. Lo decidió tu PostPolicy, no el guard.';
        } else if (e.status === 422) {
          this.errores = e.error.errors;
        } else {
          this.mensaje = `${e.status} · tu API no guardó el aviso`;
        }
      }
    });
  }
}