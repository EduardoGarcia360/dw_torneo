<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EquiposModel;

class Equipos extends BaseController
{
    protected $helpers = ['form'];
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $equipoModel = new EquiposModel();
        $data['equipos'] = $equipoModel->findAll();
        return view('equipos/index', $data);
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
        return view('equipos/nuevo');
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $equipoModel = new EquiposModel();
        
        // Validación de datos
        $reglas = [
            'nombre_equipo' => 'required|min_length[3]|max_length[100]|is_unique[equipos.nombre_equipo]'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Inserción del equipo en la base de datos
        $equipoModel->save([
            'nombre_equipo' => $this->request->getPost('nombre_equipo')
        ]);

        return redirect()->to('/equipos')->with('success', 'Equipo creado exitosamente.');
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
        $equipoModel = new EquiposModel();
        $data['equipo'] = $equipoModel->find($id);

        if (!$data['equipo']) {
            return redirect()->to('/equipos')->with('error', 'Equipo no encontrado.');
        }

        return view('equipos/editar', $data);
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
        $equipoModel = new EquiposModel();

        // Validación de datos
        $reglas = [
            'nombre_equipo' => 'required|min_length[3]|max_length[100]|is_unique[equipos.nombre_equipo,id,{id}]'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Actualización del equipo en la base de datos
        $equipoModel->update($id, [
            'nombre_equipo' => $this->request->getPost('nombre_equipo')
        ]);

        return redirect()->to('/equipos')->with('success', 'Equipo actualizado exitosamente.');
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
        $equipoModel = new EquiposModel();

        if ($id == null) {
            return redirect()->to('equipos');
        }

        // Eliminación del equipo
        if ($equipoModel->delete($id)) {
            return redirect()->to('/equipos')->with('success', 'Equipo eliminado exitosamente.');
        } else {
            return redirect()->to('/equipos')->with('error', 'No se pudo eliminar el equipo.');
        }
    }
}
