import { Component, OnInit } from '@angular/core';
import { HttpErrorResponse } from '@angular/common/http';
import { FormControl } from '@angular/forms';
import { catchError, debounceTime, distinctUntilChanged, map, of, switchMap, tap } from 'rxjs';

import { Aviso } from '../modelos/aviso';
import { AvisosService } from '../servicios/avisos.service';
import { Sesion, SesionService } from '../servicios/sesion.service';

@Component({
  selector: 'app-avisos-lista',
  templateUrl: './avisos-lista.component.html',
  styleUrls: ['./avisos-lista.component.css']
})
export class AvisosListaComponent implements OnInit {
  avisos: Aviso[] = [];
  cargando = true;
  error = '';
  mensaje = '';
  mensajeOk = false;
  buscar = new FormControl('', { nonNullable: true });

  constructor(private avisosService: AvisosService, public sesion: SesionService) { }

  ngOnInit(): void {
    this.cargar();
    this.avisosService.cambios$.subscribe(() => this.cargar());

    this.buscar.valueChanges.pipe(
      debounceTime(300),
      map(texto => texto.trim()),
      distinctUntilChanged(),
      tap(() => {
        this.cargando = true;
        this.error = '';
      }),
      switchMap(texto => this.avisosService.listar(texto).pipe(
        catchError(() => {
          this.error = 'No pude buscar en tu API.';
          return of([]);
        })
      ))
    ).subscribe(avisos => {
      this.avisos = avisos;
      this.cargando = false;
    });
  }

  cargar(): void {
    this.cargando = true;
    this.avisosService.listar(this.buscar.value.trim()).subscribe({
      next: avisos => {
        this.avisos = avisos;
        this.cargando = false;
      },
      error: () => {
        this.error = 'No pude hablar con tu API. Revisa que composer run dev siga corriendo.';
        this.cargando = false;
      }
    });
  }

  puedeBorrar(aviso: Aviso, s: Sesion | null): boolean {
    return s !== null && (s.rol === 'admin' || s.id === aviso.autor_id);
  }

  borrar(aviso: Aviso): void {
    this.mensaje = '';
    this.avisosService.borrar(aviso.id).subscribe({
      next: () => {
        this.avisos = this.avisos.filter(a => a.id !== aviso.id);
        this.mensaje = `204 · borraste "${aviso.titulo}"`;
        this.mensajeOk = true;
      },
      error: (e: HttpErrorResponse) => {
        this.mensaje = e.status === 403
          ? '403 · ese aviso no es tuyo. Lo decidió tu PostPolicy, no Angular.'
          : `${e.status} · tu API no lo borró`;
        this.mensajeOk = false;
      }
    });
  }
}