// Ejercicio de TypeScript, paso 1: JavaScript sin tipos.
// Correlo tal cual, desde frontend/:
//
//   node practica-ts/sin-tipos.js
//
// Tiene tres errores a proposito. Fijate en cuales te avisa JavaScript,
// cuando te avisa, y cual no te avisa nunca.

type Aviso = {
  id: number;
  titulo: string;
  categoria?: { id: number; nombre: string };
  creado: Date;
};

const avisos: Aviso[] = [
  { id: 1, titulo: 'Cambio de horario en barandilla', categoria: { id: 1, nombre: 'Aviso' }, creado: new Date('2026-09-10T10:00:00-06:00') },
  { id: 2, titulo: 'Curso de primeros auxilios', creado: new Date('2026-09-09T09:00:00-06:00') }
];

// La fecha en que vence un aviso: su fecha de creacion mas unos dias.
function vencimiento(fecha: Date, dias: number) {
  const resultado = new Date(fecha);
  resultado.setDate(resultado.getDate() + dias);
  return resultado;
}

// Los titulos, en mayusculas.
function titulares(lista: Aviso[]) {
  return lista.map(aviso => aviso.titulo.toUpperCase());
}

// El nombre de la categoria de cada aviso.
function categorias(lista: Aviso[]) {
  return lista.map(aviso => aviso.categoria?.nombre ?? 'Sin categoria');
}

console.log('Vence:', vencimiento(avisos[0].creado, 3));
console.log('Titulares:', titulares(avisos));
console.log('Categorias:', categorias(avisos));
