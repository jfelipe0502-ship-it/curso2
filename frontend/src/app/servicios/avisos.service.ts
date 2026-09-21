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
    const params: Record<string, string> = texto ? { q: texto } : {};

    return this.http.get<{ data: Aviso[] }>('/api/avisos', {
      params,
      context: new HttpContext().set(SIN_TOKEN, true)
    }).pipe(map(respuesta => respuesta.data));
  }

  pagina(texto = '', numero = 1): Observable<Pagina> {
    let params: HttpParams = new HttpParams().set('page', numero);
    if (texto.trim()) {
      params = params.set('q', texto.trim());
    }

    return this.http.get<{ data: Aviso[]; meta: { current_page: number; last_page: number; total: number } }>('/api/avisos', {
      params,
      context: new HttpContext().set(SIN_TOKEN, true)
    }).pipe(
      map(respuesta => ({
        avisos: respuesta.data,
        actual: respuesta.meta.current_page,
        ultima: respuesta.meta.last_page,
        total: respuesta.meta.total
      }))
    );
  }

  uno(id: number): Observable<Aviso> {
    return this.http.get<{ data: Aviso }>(`/api/avisos/${id}`, {
      context: new HttpContext().set(SIN_TOKEN, true)
    }).pipe(
      map(respuesta => respuesta.data)
    );
  }

  crear(aviso: NuevoAviso): Observable<Aviso> {
    return this.http.post<{ data: Aviso }>('/api/avisos', aviso).pipe(
      map(respuesta => respuesta.data)
    );
  }

  actualizar(id: number, aviso: NuevoAviso): Observable<Aviso> {
    return this.http.put<{ data: Aviso }>(`/api/avisos/${id}`, aviso).pipe(
      map(respuesta => respuesta.data),
      tap(() => this.cambiosSubject.next())
    );
  }

  borrar(id: number): Observable<void> {
    return this.http.delete<void>(`/api/avisos/${id}`);
  }
}
