import { Component, OnInit } from '@angular/core';

import { AvisosService } from '../servicios/avisos.service';

@Component({
  selector: 'app-practica',
  templateUrl: './practica.component.html',
  styleUrls: ['./practica.component.css']
})
export class PracticaComponent implements OnInit {
  nombre = 'Guardia nocturna';
  turnos = 3;
  pendientes = ['Revisar radios', 'Entregar reporte', 'Cambiar llantas'];
  nuevo = '';
  hoy = new Date();
  total = 0;

  constructor(private avisosService: AvisosService) { }

  ngOnInit(): void {
    this.avisosService.pagina('', 1).subscribe(respuesta => this.total = respuesta.total);
  }

  sumar(): void {
    this.turnos++;
  }

  agregar(): void {
    this.pendientes.push(this.nuevo.trim());
    this.nuevo = '';
  }
}
