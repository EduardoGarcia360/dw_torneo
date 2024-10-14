<?php

namespace App\Controllers;

use App\Models\JornadasModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class Jornadas extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $jornadasModel = new JornadasModel();
        $data['jornadas'] = $jornadasModel->findAll();
        
        return view('jornadas/index', $data);
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
        return view('jornadas/nuevo');
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $jornadasModel = new JornadasModel();

        // Validación de los datos del formulario
        if (!$this->validate([
            'numero_jornada' => 'required|integer',
            'fecha_juego' => 'required|valid_date',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Guardar la nueva jornada
        $jornadasModel->save([
            'numero_jornada' => $this->request->getPost('numero_jornada'),
            'fecha_juego' => $this->request->getPost('fecha_juego')
        ]);

        return redirect()->to('/jornadas')->with('success', 'Jornada creada exitosamente.');
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
        $jornadasModel = new JornadasModel();
        $data['jornada'] = $jornadasModel->find($id);

        if (!$data['jornada']) {
            return redirect()->to('/jornadas')->with('error', 'Jornada no encontrada.');
        }

        return view('jornadas/editar', $data);
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
        $jornadasModel = new JornadasModel();

        // Validación de los datos del formulario
        if (!$this->validate([
            'numero_jornada' => 'required|integer',
            'fecha_juego' => 'required|valid_date',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Actualizar la jornada
        $jornadasModel->update($id, [
            'numero_jornada' => $this->request->getPost('numero_jornada'),
            'fecha_juego' => $this->request->getPost('fecha_juego')
        ]);

        return redirect()->to('/jornadas')->with('success', 'Jornada actualizada exitosamente.');
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
        $jornadasModel = new JornadasModel();

        if ($jornadasModel->delete($id)) {
            return redirect()->to('/jornadas')->with('success', 'Jornada eliminada exitosamente.');
        } else {
            return redirect()->to('/jornadas')->with('error', 'No se pudo eliminar la jornada.');
        }
    }
}
