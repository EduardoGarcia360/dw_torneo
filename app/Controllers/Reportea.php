<?php

namespace App\Controllers;

use App\Models\EquiposModel;
use App\Models\IncidenciasModel;
use App\Models\JugadoresModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;
use FPDF;

class Reportea extends ResourceController
{

    public function index()
    {
        $jugadoresModel = new JugadoresModel();
        $equiposModel = new EquiposModel();
        $incidenciasModel = new IncidenciasModel();

        // Obtener la fecha de la jornada actual (esto se ajusta según la lógica de tu torneo)
        $jornadaActual = date('Y-m-d');
        
        // Obtener todos los equipos para el combo
        $equipos = $equiposModel->findAll();

        // Filtrar por equipo si se ha seleccionado uno
        $equipoSeleccionado = $this->request->getGet('equipo_id'); // Obtener equipo seleccionado desde el GET
        $jugadores = [];

        if (!empty($equipoSeleccionado)) {
            $equipo = $equiposModel->find($equipoSeleccionado); // Obtener el equipo seleccionado

            // Obtener los jugadores del equipo seleccionado
            $jugadores = $jugadoresModel->where('equipo_id', $equipoSeleccionado)->findAll();
            foreach ($jugadores as &$jugador) {
                // Verificar si el jugador está suspendido en la fecha de la jornada actual
                $suspension = $incidenciasModel->where('jugador_id', $jugador['id'])
                                               ->where('fecha_suspension >=', $jornadaActual)
                                               ->where('tipo_tarjeta', 'R')
                                               ->first();
                $jugador['suspendido'] = !empty($suspension); // True si está suspendido
            }
        }

        // Pasar los datos a la vista
        return view('reportea/index', [
            'equipos' => $equipos,
            'jugadores' => $jugadores,
            'equipoSeleccionado' => $equipoSeleccionado,
            'jornadaActual' => $jornadaActual
        ]);
    }

    public function generarReportePdf()
    {
        // Cargar la biblioteca FPDF
        require_once APPPATH . 'Libraries/fpdf186/fpdf.php';

        $jugadoresModel = new JugadoresModel();
        $equiposModel = new EquiposModel();
        $incidenciasModel = new IncidenciasModel();

        // Obtener la fecha de la jornada actual
        $jornadaActual = date('Y-m-d');

        // Obtener el equipo seleccionado
        $equipoSeleccionado = $this->request->getGet('equipo_id');
        if (empty($equipoSeleccionado)) {
            return redirect()->to('/reportea')->with('error', 'Debe seleccionar un equipo.');
        }

        // Obtener los datos del equipo y jugadores
        $equipo = $equiposModel->find($equipoSeleccionado);
        $jugadores = $jugadoresModel->where('equipo_id', $equipoSeleccionado)->findAll();

        foreach ($jugadores as &$jugador) {
            // Verificar si el jugador está suspendido en la jornada actual
            $suspension = $incidenciasModel->where('jugador_id', $jugador['id'])
                                        ->where('fecha_suspension >=', $jornadaActual)
                                        ->where('tipo_tarjeta', 'R')
                                        ->first();
            $jugador['suspendido'] = !empty($suspension); // True si está suspendido
        }

        // Iniciar FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Título del reporte
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Reporte de Equipos y Jugadores para el Árbitro', 0, 1, 'C');
        $pdf->Ln(10); // Salto de línea

        // Información de la jornada y equipo
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Jornada Actual: ' . $jornadaActual, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Equipo: ' . $equipo['nombre_equipo'], 0, 1, 'L');
        $pdf->Ln(10); // Salto de línea

        // Crear tabla de jugadores
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(20, 10, 'ID', 1);
        $pdf->Cell(30, 10, 'Foto', 1);
        $pdf->Cell(40, 10, 'Nombres', 1);
        $pdf->Cell(40, 10, 'Apellidos', 1);
        $pdf->Cell(30, 10, 'Suspension', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($jugadores as &$jugador) {
            // Agregar celda para ID
            $pdf->Cell(20, 30, $jugador['id'], 1);

            // Mostrar la imagen si existe
            $x = $pdf->GetX(); // Obtener la posición X actual
            $y = $pdf->GetY(); // Obtener la posición Y actual
            if (!empty($jugador['fotografia'])) {
                $rutaImagen = WRITEPATH . '../public/uploads/jugadores/' . $jugador['fotografia'];
                if (file_exists($rutaImagen)) {
                    // Reservar espacio para la imagen y luego colocarla en la celda
                    $pdf->Cell(30, 30, '', 1);
                    $pdf->Image($rutaImagen, $x + 5, $y + 5, 20, 20); // Imagen centrada en la celda
                } else {
                    $pdf->Cell(30, 30, 'Sin imagen', 1, 0, 'C');
                }
            } else {
                $pdf->Cell(30, 30, 'Sin imagen', 1, 0, 'C');
            }

            // Celdas de texto para los nombres, apellidos, y suspensión
            $pdf->Cell(40, 30, $jugador['nombres'], 1);
            $pdf->Cell(40, 30, $jugador['apellidos'], 1);
            $pdf->Cell(30, 30, ($jugador['suspendido']) ? 'Suspendido' : 'Disponible', 1);
            $pdf->Ln(); // Mover a la siguiente línea después de procesar toda la fila
        }

        // Salida del PDF
        $fecha = date('dmY'); // Día, Mes, Año
        $hora = date('Hisv'); // Hora, Minuto, Segundo, Milisegundo
        $random = rand(1, 10000); // Número aleatorio entre 1 y 10,000
        $pdf->Output('D', 'REPORTE1_'.$fecha.'_'.$hora.'_'.$random.'.pdf');
        exit;
    }

}
