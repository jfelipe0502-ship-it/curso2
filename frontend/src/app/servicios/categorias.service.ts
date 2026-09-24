import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';

import { Categoria } from '../modelos/categoria';

@Injectable({
  providedIn: 'root'
})
export class CategoriasService {

  constructor(private http: HttpClient) { }

  listar(): Observable<Categoria[]> {
    return this.http.get<{ results: Categoria[] }>('/api/categorias/').pipe(
      map(respuesta => respuesta.results)
    );
  }
}
