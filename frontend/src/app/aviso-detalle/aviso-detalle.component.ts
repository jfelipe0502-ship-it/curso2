import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { catchError, map, of, switchMap } from 'rxjs';

import { Aviso } from '../modelos/aviso';
import { AvisosService } from '../servicios/avisos.service';
import { SesionService } from '../servicios/sesion.service';

@Component({
  selector: 'app-aviso-detalle',
  templateUrl: './aviso-detalle.component.html',
  styleUrls: ['./aviso-detalle.component.css']
})
export class AvisoDetalleComponent implements OnInit {
  aviso: Aviso | null = null;
  cargando = true;
  error = '';

  constructor(
    private ruta: ActivatedRoute,
    private avisosService: AvisosService,
    public sesion: SesionService
  ) { }

  ngOnInit(): void {
    this.ruta.paramMap.pipe(
      map(params => Number(params.get('id'))),
      switchMap(id => this.avisosService.uno(id).pipe(
        catchError(() => {
          this.aviso = null;
          this.cargando = false;
          this.error = '404 · ese aviso no existe';
          return of(null);
        })
      ))
    ).subscribe({
      next: aviso => {
        this.cargando = false;
        if (aviso) {
          this.aviso = aviso;
          this.error = '';
          return;
        }
        this.aviso = null;
      }
    });
  }
}
