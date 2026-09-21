import { Component, EventEmitter, Input, Output } from '@angular/core';

import { Aviso } from '../modelos/aviso';

@Component({
  selector: 'app-tarjeta-aviso',
  templateUrl: './tarjeta-aviso.component.html',
  styleUrls: ['./tarjeta-aviso.component.css']
})
export class TarjetaAvisoComponent {
  @Input({ required: true }) aviso!: Aviso;
  @Input() puedeBorrar = false;

  @Output() borrar = new EventEmitter<Aviso>();
}
