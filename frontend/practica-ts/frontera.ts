export {};

interface Aviso {
  id: number;
  titulo: string;
  contenido: string;
  publicado: boolean;
  categoria?: { id: number; nombre: string };
  autor?: string;
  creado: string;
}

function esAviso(x: unknown): x is Aviso {
  return typeof x === 'object' && x !== null
    && 'id' in x && typeof x.id === 'number'
    && 'titulo' in x && typeof x.titulo === 'string'
    && 'contenido' in x && typeof x.contenido === 'string'
    && 'creado' in x && typeof x.creado === 'string';
}

function avisosDe(cuerpo: unknown): Aviso[] {
  if (typeof cuerpo !== 'object' || cuerpo === null || !('data' in cuerpo)) {
    throw new Error('La respuesta no trae el sobre "data"');
  }

  const todos = cuerpo.data;
  if (!Array.isArray(todos)) {
    throw new Error('La respuesta no trae un arreglo en "data"');
  }

  const buenos = todos.filter(esAviso);
  if (buenos.length < todos.length) {
    console.log(`  descartados: ${todos.length - buenos.length} de ${todos.length}`);
  }

  return buenos;
}

const buena: unknown = JSON.parse('{"data":[{"id":1,"titulo":"Simulacro","contenido":"A las 11","publicado":true,"creado":"2026-09-10T10:00:00-06:00"}]}');
const rota: unknown = JSON.parse('{"data":[{"id":"1","titulo":"Simulacro","contenido":"A las 11","publicado":true,"creado":"2026-09-10T10:00:00-06:00"}]}');

console.log('Buena:', avisosDe(buena).length);
console.log('Rota:', avisosDe(rota).length);

async function deTuApi(): Promise<void> {
  const respuesta = await fetch('http://127.0.0.1:8000/api/avisos');
  const cuerpo: unknown = await respuesta.json();
  console.log('Tu API:', avisosDe(cuerpo).length, 'avisos con la forma de Aviso');
}

deTuApi();

type Errores = Record<string, string[]>;

type Estado =
  | { tipo: 'cargando' }
  | { tipo: 'listo'; avisos: Aviso[] }
  | { tipo: 'invalido'; errores: Errores }
  | { tipo: 'fallo'; codigo: number };

function mensaje(estado: Estado): string {
  switch (estado.tipo) {
    case 'cargando':
      return 'Cargando avisos...';
    case 'listo':
      return `${estado.avisos.length} avisos`;
    case 'invalido':
      return Object.values(estado.errores).flat().join(' ');
    case 'fallo':
      return `Tu API respondió ${estado.codigo}`;
    default: {
      const olvidado: never = estado;
      return olvidado;
    }
  }
}

const estados: Estado[] = [
  { tipo: 'cargando' },
  { tipo: 'listo', avisos: avisosDe(buena) },
  { tipo: 'invalido', errores: { titulo: ['The titulo field is required.'], contenido: ['The contenido field is required.'] } },
  { tipo: 'fallo', codigo: 403 }
];

for (const estado of estados) {
  console.log(`[${estado.tipo}]`, mensaje(estado));
}

type NuevoAviso = Pick<Aviso, 'titulo' | 'contenido'> & { categoria_id: number };
type Renglon = Pick<Aviso, 'id' | 'titulo'>;
type Borrador = Partial<NuevoAviso>;

const paraCrear: NuevoAviso = { titulo: 'Simulacro de sismo', contenido: 'A las 11, en el patio', categoria_id: 1 };
const aMedias: Borrador = { titulo: 'Simulacro de sismo' };
const renglones: Renglon[] = avisosDe(buena).map(({ id, titulo }) => ({ id, titulo }));

console.log('Para el POST:', paraCrear);
console.log('A medias:', aMedias);
console.log('Renglones:', renglones);
