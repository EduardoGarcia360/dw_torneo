<?php

namespace App\Controllers;

use App\Models\EquiposModel;
use App\Models\IncidenciasModel;
use App\Models\JornadasModel;
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
        $jornadasModel = new JornadasModel();

        $jornada = $jornadasModel->jornadaVigente();

        $jornadaActual = $jornada['fecha_juego'];
        $numeroJornada = $jornada['numero_jornada'];
        
        $equipos = $equiposModel->findAll();

        $equipoSeleccionado = $this->request->getGet('equipo_id');
        $jugadores = [];

        if (!empty($equipoSeleccionado)) {
            $equipo = $equiposModel->find($equipoSeleccionado);

            $jugadores = $jugadoresModel->where('equipo_id', $equipoSeleccionado)->findAll();
            foreach ($jugadores as &$jugador) {
                $suspension = $incidenciasModel->where('jugador_id', $jugador['id'])
                                               ->where('fecha_suspension >=', $jornadaActual)
                                               ->where('tipo_tarjeta', 'R')
                                               ->first();
                $jugador['suspendido'] = !empty($suspension);
            }
        }

        // Pasar los datos a la vista
        return view('reportea/index', [
            'equipos' => $equipos,
            'jugadores' => $jugadores,
            'equipoSeleccionado' => $equipoSeleccionado,
            'jornadaActual' => $jornadaActual,
            'numeroJornada' => $numeroJornada
        ]);
    }

    public function generarReportePdf()
    {
        // Cargar la biblioteca FPDF
        require_once APPPATH . 'Libraries/fpdf186/fpdf.php';

        $jugadoresModel = new JugadoresModel();
        $equiposModel = new EquiposModel();
        $incidenciasModel = new IncidenciasModel();
        $jornadasModel = new JornadasModel();

        $jornada = $jornadasModel->jornadaVigente();

        $jornadaActual = $jornada['fecha_juego'];
        $numeroJornada = $jornada['numero_jornada'];

        $equipoSeleccionado = $this->request->getGet('equipo_id');
        if (empty($equipoSeleccionado)) {
            return redirect()->to('/reportea')->with('error', 'Debe seleccionar un equipo.');
        }

        $equipo = $equiposModel->find($equipoSeleccionado);
        $jugadores = $jugadoresModel->where('equipo_id', $equipoSeleccionado)->findAll();

        foreach ($jugadores as &$jugador) {
            $suspension = $incidenciasModel->where('jugador_id', $jugador['id'])
                                        ->where('fecha_suspension >=', $jornadaActual)
                                        ->where('tipo_tarjeta', 'R')
                                        ->first();
            $jugador['suspendido'] = !empty($suspension);
        }

        // Iniciar FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Título del reporte
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Reporte de Equipos y Jugadores para el Árbitro'), 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Jornada Actual: ' . $jornadaActual.' ('.$numeroJornada.')', 0, 1, 'L');
        $pdf->Cell(0, 10, 'Equipo: ' . iconv('UTF-8', 'ISO-8859-1', $equipo['nombre_equipo']), 0, 1, 'L');
        $pdf->Ln(10);

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
            $pdf->Cell(20, 30, $jugador['id'], 1);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            if (!empty($jugador['fotografia'])) {
                $rutaImagen = WRITEPATH . '../public/uploads/jugadores/' . $jugador['fotografia'];
                if (file_exists($rutaImagen)) {
                    $pdf->Cell(30, 30, '', 1);
                    $pdf->Image($rutaImagen, $x + 5, $y + 5, 20, 20);
                } else {
                    $pdf->Cell(30, 30, 'Sin imagen', 1, 0, 'C');
                }
            } else {
                $pdf->Cell(30, 30, 'Sin imagen', 1, 0, 'C');
            }

            $pdf->Cell(40, 30, iconv('UTF-8', 'ISO-8859-1', $jugador['nombres']), 1);
            $pdf->Cell(40, 30, iconv('UTF-8', 'ISO-8859-1', $jugador['apellidos']), 1);
            $pdf->Cell(30, 30, ($jugador['suspendido']) ? 'Suspendido' : 'Disponible', 1);
            $pdf->Ln();
        }

        // Salida del PDF
        $fecha = date('dmY'); // Día, Mes, Año
        $hora = date('Hisv'); // Hora, Minuto, Segundo, Milisegundo
        $random = rand(1, 10000); // Número aleatorio entre 1 y 10,000
        $pdf->Output('D', 'REPORTE1_'.$fecha.'_'.$hora.'_'.$random.'.pdf');
        exit;
    }

}
