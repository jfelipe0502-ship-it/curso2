import { Injectable } from '@angular/core';
import {
  HttpHandler,
  HttpEvent,
  HttpInterceptor,
  HttpRequest
} from '@angular/common/http';
import { finalize, Observable } from 'rxjs';

@Injectable()
export class TiempoInterceptor implements HttpInterceptor {

  intercept(request: HttpRequest<unknown>, next: HttpHandler): Observable<HttpEvent<unknown>> {
    const inicio = performance.now();

    return next.handle(request).pipe(
      finalize(() => {
        const duracion = Math.round(performance.now() - inicio);
        console.log(`${request.method} ${request.urlWithParams} · ${duracion} ms`);
      })
    );
  }
}
