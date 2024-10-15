<?php

namespace App\Controllers;

use App\Models\IncidenciasModel;
use App\Models\JugadoresModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class Incidencias extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $incidenciasModel = new IncidenciasModel();
        $data['incidencias'] = $incidenciasModel->incidenciasJugadores();
        
        return view('incidencias/index', $data);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        $jugadoresModel = new JugadoresModel();
        $data['jugadores'] = $jugadoresModel->findAll();

        return view('incidencias/nuevo', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $incidenciasModel = new IncidenciasModel();

        // Validación de los datos del formulario
        if (!$this->validate([
            'jugador_id' => 'required|integer',
            'descripcion' => 'required|string',
            'tipo_tarjeta' => 'required|in_list[R,A]',
            'fecha_incidencia' => 'required|valid_date',
            'fecha_suspension' => 'permit_empty|valid_date',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Guardar la nueva incidencia
        $incidenciasModel->save([
            'jugador_id' => $this->request->getPost('jugador_id'),
            'descripcion' => $this->request->getPost('descripcion'),
            'tipo_tarjeta' => $this->request->getPost('tipo_tarjeta'),
            'fecha_incidencia' => $this->request->getPost('fecha_incidencia'),
            'fecha_suspension' => $this->request->getPost('fecha_suspension')
        ]);

        return redirect()->to('/incidencias')->with('success', 'Incidencia registrada exitosamente.');
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        $incidenciasModel = new IncidenciasModel();
        $jugadoresModel = new JugadoresModel();

        $data['incidencia'] = $incidenciasModel->find($id);
        $data['jugadores'] = $jugadoresModel->findAll();

        if (!$data['incidencia']) {
            return redirect()->to('/incidencias')->with('error', 'Incidencia no encontrada.');
        }

        return view('incidencias/editar', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        $incidenciasModel = new IncidenciasModel();

        // Validación de los datos del formulario
        if (!$this->validate([
            'jugador_id' => 'required|integer',
            'descripcion' => 'required|string',
            'tipo_tarjeta' => 'required|in_list[R,A]',
            'fecha_incidencia' => 'required|valid_date',
            'fecha_suspension' => 'permit_empty|valid_date',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Actualizar la incidencia
        $incidenciasModel->update($id, [
            'jugador_id' => $this->request->getPost('jugador_id'),
            'descripcion' => $this->request->getPost('descripcion'),
            'tipo_tarjeta' => $this->request->getPost('tipo_tarjeta'),
            'fecha_incidencia' => $this->request->getPost('fecha_incidencia'),
            'fecha_suspension' => $this->request->getPost('fecha_suspension')
        ]);

        return redirect()->to('/incidencias')->with('success', 'Incidencia actualizada exitosamente.');
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        $incidenciasModel = new IncidenciasModel();

        if ($incidenciasModel->delete($id)) {
            return redirect()->to('/incidencias')->with('success', 'Incidencia eliminada exitosamente.');
        } else {
            return redirect()->to('/incidencias')->with('error', 'No se pudo eliminar la incidencia.');
        }
    }
}
