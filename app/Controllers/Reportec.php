<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EquiposModel;
use App\Models\GolesModel;
use App\Models\JugadoresModel;
use CodeIgniter\HTTP\ResponseInterface;
use FPDF;

class Reportec extends BaseController
{
    public function index()
    {
        $golesModel = new GolesModel();
        $jugadoresModel = new JugadoresModel();
        $equiposModel = new EquiposModel();

        // Consulta para totalizar los goles por jugador, ordenado de mayor a menor
        $goleadores = $golesModel->select('jugadores.id as jugador_id, jugadores.nombres as jugador_nombre, jugadores.apellidos as jugador_apellidos, jugadores.fotografia, equipos.nombre_equipo, SUM(goles.cantidad_goles) as total_goles')
                                  ->join('jugadores', 'goles.jugador_id = jugadores.id')
                                  ->join('equipos', 'jugadores.equipo_id = equipos.id')
                                  ->groupBy('jugadores.id')
                                  ->orderBy('total_goles', 'DESC')
                                  ->findAll();

        // Pasar los datos a la vista
        return view('reportec/index', [
            'goleadores' => $goleadores
        ]);
    }

    public function generar_pdf()
    {
        // Cargar la librería FPDF
        require_once APPPATH . 'Libraries/fpdf186/fpdf.php';

        $golesModel = new GolesModel();

        // Consulta para totalizar los goles por jugador, ordenado de mayor a menor
        $goleadores = $golesModel->select('jugadores.nombres as jugador_nombre, jugadores.apellidos as jugador_apellidos, jugadores.fotografia, equipos.nombre_equipo, SUM(goles.cantidad_goles) as total_goles')
                                  ->join('jugadores', 'goles.jugador_id = jugadores.id')
                                  ->join('equipos', 'jugadores.equipo_id = equipos.id')
                                  ->groupBy('jugadores.id')
                                  ->orderBy('total_goles', 'DESC')
                                  ->findAll();

        // Crear una nueva instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Título del PDF
        $pdf->Cell(0, 10, 'Reporte de Goleadores', 0, 1, 'C');
        $pdf->Ln(10);

        // Encabezados de la tabla
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, iconv('UTF-8', 'ISO-8859-1', 'Fotografía'), 1);
        $pdf->Cell(60, 10, 'Jugador', 1);
        $pdf->Cell(50, 10, 'Equipo', 1);
        $pdf->Cell(30, 10, 'Goles Totales', 1);
        $pdf->Ln();

        // Cargar los datos
        $pdf->SetFont('Arial', '', 12);
        foreach ($goleadores as $goleador) {
            // Mostrar la fotografía si existe
            if (!empty($goleador['fotografia'])) {
                $rutaFoto = 'uploads/jugadores/' . $goleador['fotografia'];
                if (file_exists($rutaFoto)) {
                    $pdf->Cell(40, 30, $pdf->Image(base_url($rutaFoto), $pdf->GetX(), $pdf->GetY(), 30, 30), 1);
                } else {
                    $pdf->Cell(40, 30, 'Sin Foto', 1, 0, 'C');
                }
            } else {
                $pdf->Cell(40, 30, 'Sin Foto', 1, 0, 'C');
            }

            $pdf->Cell(60, 30, iconv('UTF-8', 'ISO-8859-1', $goleador['jugador_nombre']) . ' ' . iconv('UTF-8', 'ISO-8859-1', $goleador['jugador_apellidos']), 1);
            $pdf->Cell(50, 30, iconv('UTF-8', 'ISO-8859-1', $goleador['nombre_equipo']), 1);
            $pdf->Cell(30, 30, $goleador['total_goles'], 1);
            $pdf->Ln();
        }

        // Generar nombre del archivo PDF
        $fecha = date('dmY'); // Día, Mes, Año
        $hora = date('Hisv'); // Hora, Minuto, Segundo, Milisegundo
        $random = rand(1, 10000); // Número aleatorio entre 1 y 10,000

        // Salida del archivo PDF
        $pdf->Output('D', 'REPORTE3_' . $fecha . '_' . $hora . '_' . $random . '.pdf');
    }

}
