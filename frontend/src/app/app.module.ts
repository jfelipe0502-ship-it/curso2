import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { AuthInterceptor } from './interceptores/auth.interceptor';
import { ErroresInterceptor } from './interceptores/errores.interceptor';
import { TiempoInterceptor } from './interceptores/tiempo.interceptor';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { EntrarComponent } from './entrar/entrar.component';
import { AvisosListaComponent } from './avisos-lista/avisos-lista.component';
import { AvisoNuevoComponent } from './aviso-nuevo/aviso-nuevo.component';
import { PracticaComponent } from './practica/practica.component';
import { AvisoDetalleComponent } from './aviso-detalle/aviso-detalle.component';
import { AvisoEditarComponent } from './aviso-editar/aviso-editar.component';
import { TarjetaAvisoComponent } from './tarjeta-aviso/tarjeta-aviso.component';

@NgModule({
  declarations: [
    AppComponent,
    EntrarComponent,
    AvisosListaComponent,
    AvisoNuevoComponent,
    PracticaComponent,
    AvisoDetalleComponent,
    AvisoEditarComponent,
    TarjetaAvisoComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    AppRoutingModule
  ],
  providers: [
    { provide: HTTP_INTERCEPTORS, useClass: TiempoInterceptor, multi: true },
    { provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true },
    { provide: HTTP_INTERCEPTORS, useClass: ErroresInterceptor, multi: true },
  ],
  bootstrap: [AppComponent]
})

export class AppModule { }
