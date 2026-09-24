import { Injectable } from '@angular/core';
import { HttpClient, HttpContext, HttpParams } from '@angular/common/http';
import { Observable, Subject, map, tap } from 'rxjs';

import { Aviso, NuevoAviso } from '../modelos/aviso';
import { SIN_TOKEN } from '../interceptores/auth.interceptor';

export interface Pagina {
  avisos: Aviso[];
  actual: number;
  ultima: number;
  total: number;
}

@Injectable({
  providedIn: 'root'
})
export class AvisosService {

  private cambiosSubject = new Subject<void>();
  readonly cambios$ = this.cambiosSubject.asObservable();

  constructor(private http: HttpClient) { }

  listar(texto = ''): Observable<Aviso[]> {
    const params: Record<string, string> = texto ? { buscar: texto } : {};

    return this.http.get<{ results: Aviso[] }>('/api/avisos/', {
      params,
      context: new HttpContext().set(SIN_TOKEN, true)
    }).pipe(map(respuesta => respuesta.results));
  }

  pagina(texto = '', numero = 1): Observable<Pagina> {
    let params: HttpParams = new HttpParams().set('page', numero);
    if (texto.trim()) {
      params = params.set('buscar', texto.trim());
    }

    return this.http.get<{ results: Aviso[]; count: number }>('/api/avisos/', {
      params,
      context: new HttpContext().set(SIN_TOKEN, true)
    }).pipe(
      map(respuesta => ({
        avisos: respuesta.results,
        actual: numero,
        ultima: Math.ceil(respuesta.count / 10),
        total: respuesta.count
      }))
    );
  }

  uno(id: number): Observable<Aviso> {
    return this.http.get<Aviso>(`/api/avisos/${id}/`, {
      context: new HttpContext().set(SIN_TOKEN, true)
    });
  }

  crear(aviso: NuevoAviso): Observable<Aviso> {
    return this.http.post<Aviso>('/api/avisos/', aviso);
  }

  actualizar(id: number, aviso: NuevoAviso): Observable<Aviso> {
    return this.http.put<Aviso>(`/api/avisos/${id}/`, aviso).pipe(
      tap(() => this.cambiosSubject.next())
    );
  }

  borrar(id: number): Observable<void> {
    return this.http.delete<void>(`/api/avisos/${id}/`);
  }
}
