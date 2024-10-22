<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GolesModel;
use App\Models\IncidenciasModel;
use App\Models\JugadoresModel;
use CodeIgniter\HTTP\ResponseInterface;
use FPDF;

class Reported extends BaseController
{
    public function index()
    {
        //
    }

    public function listarJugadores()
    {
        $jugadoresModel = new JugadoresModel();
        $incidenciasModel = new IncidenciasModel();
        $golesModel = new GolesModel();

        // Obtener todos los jugadores con sus equipos
        $jugadores = $jugadoresModel->select('jugadores.*, equipos.nombre_equipo, equipos.id as equipo_id')
                                    ->join('equipos', 'jugadores.equipo_id = equipos.id')
                                    ->findAll();

        // Obtener todas las incidencias
        $incidencias = $incidenciasModel->findAll();

        // Obtener todos los goles
        $goles = $golesModel->select('goles.*, jornadas.numero_jornada, jornadas.fecha_juego as fecha_jornada')
                            ->join('jornadas', 'goles.jornada_id = jornadas.id')
                            ->findAll();

        // Asignar incidencias y goles a los jugadores
        foreach ($jugadores as &$jugador) {
            // Asegurarse de que incidencias sea un arreglo de objetos
            $jugador['incidencias'] = array_values(array_filter($incidencias, function($incidencia) use ($jugador) {
                return $incidencia['jugador_id'] == $jugador['id'];
            }));

            // Asegurarse de que goles sea un arreglo de objetos
            $jugador['goles'] = array_values(array_filter($goles, function($gol) use ($jugador) {
                return $gol['jugador_id'] == $jugador['id'];
            }));
        }

        // Pasar los datos a la vista
        return view('reported/index', [
            'jugadores' => $jugadores
        ]);
    }

    public function generar_pdf()
    {
        // Cargar la biblioteca FPDF
        require_once APPPATH . 'Libraries/fpdf186/fpdf.php';

        $jugadoresModel = new JugadoresModel();
        $incidenciasModel = new IncidenciasModel();
        $golesModel = new GolesModel();

        // Obtener el jugador seleccionado desde el formulario
        $jugadorId = $this->request->getGet('jugador_id');

        // Obtener los datos del jugador
        $jugador = $jugadoresModel->select('jugadores.*, equipos.nombre_equipo')
                                  ->join('equipos', 'jugadores.equipo_id = equipos.id')
                                  ->where('jugadores.id', $jugadorId)
                                  ->first();

        // Obtener las incidencias del jugador
        $incidencias = $incidenciasModel->where('jugador_id', $jugadorId)->findAll();

        // Obtener los goles del jugador
        $goles = $golesModel->select('goles.*, jornadas.numero_jornada, jornadas.fecha_juego as fecha_jornada')
                            ->join('jornadas', 'goles.jornada_id = jornadas.id')
                            ->where('goles.jugador_id', $jugadorId)
                            ->findAll();

        // Crear el PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Título del reporte
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Reporte de Información del Jugador'), 0, 1, 'C');
        $pdf->Ln(10);

        // Datos del jugador
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Nombre: ' . iconv('UTF-8', 'ISO-8859-1', $jugador['nombres']) . ' ' . iconv('UTF-8', 'ISO-8859-1', $jugador['apellidos']), 0, 1);
        $pdf->Cell(0, 10, 'Fecha de Nacimiento: ' . $jugador['fecha_nacimiento'], 0, 1);
        $pdf->Cell(0, 10, 'Equipo: ' . iconv('UTF-8', 'ISO-8859-1', $jugador['nombre_equipo']), 0, 1);
        $pdf->Ln(10);

        // Mostrar la fotografía del jugador si existe
        if (!empty($jugador['fotografia'])) {
            $rutaFoto = FCPATH . 'uploads/jugadores/' . $jugador['fotografia'];
            if (file_exists($rutaFoto)) {
                $pdf->Image($rutaFoto, 10, $pdf->GetY(), 30, 30); // Posición X, Y y tamaño de la imagen
                $pdf->Ln(35); // Añadir un espacio después de la imagen
            } else {
                $pdf->Cell(0, 10, 'Fotografía no disponible.', 0, 1);
                $pdf->Ln(10);
            }
        }

        // Incidencias
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Incidencias:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        foreach ($incidencias as $incidencia) {
            $pdf->Cell(0, 10, 'Descripcion: ' . $incidencia['descripcion'], 0, 1);
            $pdf->Cell(0, 10, 'Tipo de Tarjeta: ' . ($incidencia['tipo_tarjeta'] == 'R' ? 'Roja' : 'Amarilla'), 0, 1);
            $pdf->Cell(0, 10, 'Fecha de Incidencia: ' . $incidencia['fecha_incidencia'], 0, 1);
            $pdf->Ln(5);
        }

        // Goles
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Goles:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        foreach ($goles as $gol) {
            $pdf->Cell(0, 10, 'Cantidad de goles: ' . $gol['cantidad_goles'], 0, 1);
            $pdf->Cell(0, 10, 'Jornada: ' . $gol['numero_jornada'], 0, 1);
            $pdf->Cell(0, 10, 'Fecha de Jornada: ' . $gol['fecha_jornada'], 0, 1);
            $pdf->Ln(5);
        }

        // Salida del archivo PDF
        $fecha = date('dmY'); // Día, Mes, Año
        $hora = date('Hisv'); // Hora, Minuto, Segundo, Milisegundo
        $random = rand(1, 10000); // Número aleatorio entre 1 y 10,000

        $pdf->Output('D', 'REPORTE4_' . $fecha . '_' . $hora . '_' . $random . '.pdf');
    }

}
