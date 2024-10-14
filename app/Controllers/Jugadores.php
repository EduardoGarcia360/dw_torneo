<?php

namespace App\Controllers;

use App\Models\EquiposModel;
use App\Models\JugadoresModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class Jugadores extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $jugadoresModel = new JugadoresModel();
        $data['jugadores'] = $jugadoresModel->jugadoresEquipos();
        
        return view('jugadores/index', $data);
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
        $equiposModel = new EquiposModel();
        $data['equipos'] = $equiposModel->findAll();
        
        return view('jugadores/nuevo', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $jugadoresModel = new JugadoresModel();
        
        // Validación de los datos del formulario
        if (!$this->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'fecha_nacimiento' => 'required|valid_date',
            'equipo_id' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Guardar el nuevo jugador
        $jugadoresModel->save([
            'nombres' => $this->request->getPost('nombres'),
            'apellidos' => $this->request->getPost('apellidos'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'equipo_id' => $this->request->getPost('equipo_id')
        ]);

        return redirect()->to('/jugadores')->with('success', 'Jugador creado exitosamente.');
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
        $jugadoresModel = new JugadoresModel();
        $equiposModel = new EquiposModel();

        $data['jugador'] = $jugadoresModel->find($id);
        $data['equipos'] = $equiposModel->findAll();

        if (!$data['jugador']) {
            return redirect()->to('/jugadores')->with('error', 'Jugador no encontrado.');
        }

        return view('jugadores/editar', $data);
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
        $jugadoresModel = new JugadoresModel();

        // Validación de los datos del formulario
        if (!$this->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'fecha_nacimiento' => 'required|valid_date',
            'equipo_id' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Actualizar jugador
        $jugadoresModel->update($id, [
            'nombres' => $this->request->getPost('nombres'),
            'apellidos' => $this->request->getPost('apellidos'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'equipo_id' => $this->request->getPost('equipo_id')
        ]);

        return redirect()->to('/jugadores')->with('success', 'Jugador actualizado exitosamente.');
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
        $jugadoresModel = new JugadoresModel();

        if ($jugadoresModel->delete($id)) {
            return redirect()->to('/jugadores')->with('success', 'Jugador eliminado exitosamente.');
        } else {
            return redirect()->to('/jugadores')->with('error', 'No se pudo eliminar el jugador.');
        }
    }
}
