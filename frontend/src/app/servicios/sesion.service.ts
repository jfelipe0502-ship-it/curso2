import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable, map, switchMap, tap } from 'rxjs';

export interface Sesion {
  token: string;
  id: number;
  usuario: string;
  rol: string;
}

const CLAVE = 'sesion';

@Injectable({
  providedIn: 'root'
})
export class SesionService {

  // La sesion actual, o null si nadie ha entrado. Quien se suscribe a sesion$
  // se entera cada vez que cambia.
  private sesionSubject = new BehaviorSubject<Sesion | null>(this.leerGuardada());
  readonly sesion$ = this.sesionSubject.asObservable();

  constructor(private http: HttpClient) { }

  get token(): string | null {
    return this.sesionSubject.value?.token ?? null;
  }

  entrar(email: string, password: string): Observable<Sesion> {
    return this.http.post<{ token: string }>('/api/token', { username: email, password }).pipe(
      switchMap(({ token }) => {
        const provisional: Sesion = { token, id: 0, usuario: email, rol: '' };
        sessionStorage.setItem(CLAVE, JSON.stringify(provisional));
        this.sesionSubject.next(provisional);
        return this.yo().pipe(
          map(yo => ({ token, id: yo.id, usuario: yo.nombre, rol: yo.rol }))
        );
      }),
      tap(sesion => {
        sessionStorage.setItem(CLAVE, JSON.stringify(sesion));
        this.sesionSubject.next(sesion);
      })
    );
  }

  yo(): Observable<{ id: number; nombre: string; rol: string }> {
    return this.http.get<{ id: number; nombre: string; rol: string }>('/api/yo');
  }

  salir(): void {
    this.http.post('/api/token/revocar', {}).subscribe({
      next: () => this.olvidar(),
      error: () => this.olvidar()
    });
  }

  olvidar(): void {
    sessionStorage.removeItem(CLAVE);
    this.sesionSubject.next(null);
  }

  private leerGuardada(): Sesion | null {
    const guardada = sessionStorage.getItem(CLAVE);
    return guardada ? JSON.parse(guardada) : null;
  }
}