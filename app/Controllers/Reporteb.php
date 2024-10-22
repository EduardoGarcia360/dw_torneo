<?php

namespace App\Controllers;

use App\Models\EquiposModel;
use App\Models\IncidenciasModel;
use App\Models\JugadoresModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;
use FPDF;

class Reporteb extends ResourceController
{

    public function index()
    {
        $equiposModel = new EquiposModel();
        $incidenciasModel = new IncidenciasModel();
        $jugadoresModel = new JugadoresModel();
    
        // Obtener todos los equipos para el combo
        $equipos = $equiposModel->findAll();
    
        // Obtener todas las incidencias con los datos de los jugadores
        $incidencias = $incidenciasModel->select('incidencias.*, jugadores.nombres as jugador_nombre, jugadores.apellidos as jugador_apellidos, jugadores.fotografia, jugadores.equipo_id, equipos.nombre_equipo')
                                        ->join('jugadores', 'jugadores.id = incidencias.jugador_id')
                                        ->join('equipos', 'equipos.id = jugadores.equipo_id')
                                        ->findAll();
    
        // Pasar todos los datos a la vista
        return view('reporteb/index', [
            'equipos' => $equipos,
            'incidencias' => $incidencias,
        ]);
    }

    public function generar_pdf()
    {
        // Cargar la biblioteca FPDF
        require_once APPPATH . 'Libraries/fpdf186/fpdf.php';
    
        $equiposModel = new EquiposModel();
        $incidenciasModel = new IncidenciasModel();
    
        // Obtener equipo y jugador seleccionados desde el formulario
        $equipoId = $this->request->getGet('equipo_id');
        $jugadorId = $this->request->getGet('jugador_id');
    
        // Obtener todas las incidencias con la información de los jugadores y equipos
        $incidencias = $incidenciasModel->select('incidencias.*, jugadores.nombres as jugador_nombre, jugadores.apellidos as jugador_apellidos, jugadores.fotografia, equipos.nombre_equipo, equipos.id as equipo_id, jugadores.id as jugador_id')
                                        ->join('jugadores', 'jugadores.id = incidencias.jugador_id')
                                        ->join('equipos', 'equipos.id = jugadores.equipo_id')
                                        ->findAll();
    
        // Filtrar incidencias por equipo si se selecciona uno
        if (!empty($equipoId)) {
            $incidencias = array_filter($incidencias, function($incidencia) use ($equipoId) {
                return $incidencia['equipo_id'] == $equipoId;
            });
        }
    
        // Filtrar incidencias por jugador si se selecciona uno
        if (!empty($jugadorId)) {
            $incidencias = array_filter($incidencias, function($incidencia) use ($jugadorId) {
                return $incidencia['jugador_id'] == $jugadorId;
            });
        }
    
        // Cargar la librería FPDF
        $pdf = new \FPDF();
        $pdf->AddPage();
    
        // Título del reporte
        $pdf->SetFont('Arial', 'B', 16);
        $equipoNombre = empty($equipoId) ? 'Todos los equipos' : iconv('UTF-8', 'ISO-8859-1', $equiposModel->find($equipoId)['nombre_equipo']);
        $pdf->Cell(0, 10, 'Reporte de Incidencias - ' . $equipoNombre, 0, 1, 'C');
        $pdf->Ln(10);
    
        // Tabla de incidencias
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(10, 10, 'ID', 1);
        $pdf->Cell(30, 10, 'Foto', 1);
        $pdf->Cell(40, 10, 'Jugador', 1);
        $pdf->Cell(60, 10, iconv('UTF-8', 'ISO-8859-1', 'Descripción'), 1);
        $pdf->Cell(30, 10, 'Tarjeta', 1);
        $pdf->Cell(50, 10, 'Fecha de Incidencia', 1);
        $pdf->Ln();
    
        // Datos de incidencias
        $pdf->SetFont('Arial', '', 12);
        foreach ($incidencias as $incidencia) {
            $pdf->Cell(10, 30, $incidencia['id'], 1);

            // Mostrar la imagen si existe
            $x = $pdf->GetX(); // Obtener la posición X actual
            $y = $pdf->GetY(); // Obtener la posición Y actual
            if (!empty($incidencia['fotografia'])) {
                $rutaImagen = WRITEPATH . '../public/uploads/jugadores/' . $incidencia['fotografia'];
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

            $pdf->Cell(40, 30, iconv('UTF-8', 'ISO-8859-1', $incidencia['jugador_nombre']) . ' ' . iconv('UTF-8', 'ISO-8859-1', $incidencia['jugador_apellidos']), 1);
            $pdf->Cell(60, 30, iconv('UTF-8', 'ISO-8859-1', $incidencia['descripcion']), 1);
            $pdf->Cell(30, 30, ($incidencia['tipo_tarjeta'] == 'R') ? 'Roja' : 'Amarilla', 1);
            $pdf->Cell(50, 30, $incidencia['fecha_incidencia'], 1);
            $pdf->Ln();
        }
    
        // Salida del archivo PDF
        $fecha = date('dmY'); // Día, Mes, Año
        $hora = date('Hisv'); // Hora, Minuto, Segundo, Milisegundo
        $random = rand(1, 10000); // Número aleatorio entre 1 y 10,000
    
        $pdf->Output('D', 'REPORTE2_'.$fecha.'_'.$hora.'_'.$random.'.pdf');
    }

}
