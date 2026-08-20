<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Datos de práctica del blog de avisos.
     *
     * Ojo: asume las columnas del ejercicio de clase (titulo, contenido,
     * categoria_id). Si nombraste distinto tus columnas, adapta este archivo.
     * Las fechas van escalonadas a propósito: sirven para probar latest()
     * y el scope recientes() de la tarea.
     */
    public function run(): void
    {
        $avisos = [
            ['titulo' => 'Cambio de horario en barandilla', 'categoria' => 'Aviso', 'dias' => 1,
             'contenido' => 'A partir del lunes el turno nocturno inicia a las 21:00 horas para todo el personal operativo.'],
            ['titulo' => 'Curso de primeros auxilios', 'categoria' => 'Capacitación', 'dias' => 2,
             'contenido' => 'Inscripciones abiertas en la academia para el curso de primeros auxilios básicos. Cupo limitado a 25 personas.'],
            ['titulo' => 'Mantenimiento de patrullas', 'categoria' => 'Operativo', 'dias' => 4,
             'contenido' => 'Las unidades del sector centro pasan a revisión mecánica esta semana según el rol publicado en cada comandancia.'],
            ['titulo' => 'Actualización del directorio interno', 'categoria' => 'Aviso', 'dias' => 6,
             'contenido' => 'Revisa que tu extensión y correo institucional aparezcan correctos en el directorio; los cambios se reportan a sistemas.'],
            ['titulo' => 'Taller de manejo defensivo', 'categoria' => 'Capacitación', 'dias' => 9,
             'contenido' => 'Nueva fecha para el taller de manejo defensivo dirigido a operadores de unidades. Registro con tu jefe de turno.'],
            ['titulo' => 'Operativo coordinado en el sector norte', 'categoria' => 'Operativo', 'dias' => 12,
             'contenido' => 'El viernes se realiza un operativo coordinado con protección civil en el sector norte; consulta el rol de participación.'],
            ['titulo' => 'Renovación de credenciales', 'categoria' => 'Aviso', 'dias' => 20,
             'contenido' => 'El módulo de credencialización atiende de 9:00 a 14:00. Lleva una fotografía reciente y tu credencial anterior.'],
            ['titulo' => 'Simulacro de evacuación en oficinas centrales', 'categoria' => 'Operativo', 'dias' => 40,
             'contenido' => 'Simulacro general en oficinas centrales; al sonar la alarma sigue las rutas marcadas y repórtate con tu brigadista.'],
        ];

        $categorias = Categoria::pluck('id', 'nombre');

        foreach ($avisos as $datos) {
            $post = Post::firstOrNew(['titulo' => $datos['titulo']]);
            $post->contenido = $datos['contenido'];
            $post->categoria_id = $categorias[$datos['categoria']];
            $post->created_at = now()->subDays($datos['dias']);
            $post->save();
        }
    }
}
