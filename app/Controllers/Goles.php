<?php

namespace App\Controllers;

use App\Models\GolesModel;
use App\Models\JornadasModel;
use App\Models\JugadoresModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class Goles extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $golesModel = new GolesModel();
        $data['goles'] = $golesModel->golesJugadorJornada();

        return view('goles/index', $data);
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
        $jornadasModel = new JornadasModel();

        $data['jugadores'] = $jugadoresModel->findAll();
        $data['jornadas'] = $jornadasModel->findAll();
        
        return view('goles/nuevo', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $golesModel = new GolesModel();

        // Validación de los datos del formulario
        if (!$this->validate([
            'jugador_id' => 'required|integer',
            'jornada_id' => 'required|integer',
            'cantidad_goles' => 'required|integer|min_length[1]',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Guardar el nuevo gol
        $golesModel->save([
            'jugador_id' => $this->request->getPost('jugador_id'),
            'jornada_id' => $this->request->getPost('jornada_id'),
            'cantidad_goles' => $this->request->getPost('cantidad_goles')
        ]);

        return redirect()->to('/goles')->with('success', 'Gol registrado exitosamente.');
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
        $golesModel = new GolesModel();
        $jugadoresModel = new JugadoresModel();
        $jornadasModel = new JornadasModel();

        $data['gol'] = $golesModel->find($id);
        $data['jugadores'] = $jugadoresModel->findAll();
        $data['jornadas'] = $jornadasModel->findAll();

        if (!$data['gol']) {
            return redirect()->to('/goles')->with('error', 'Registro de gol no encontrado.');
        }

        return view('goles/editar', $data);
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
        $golesModel = new GolesModel();

        // Validación de los datos del formulario
        if (!$this->validate([
            'jugador_id' => 'required|integer',
            'jornada_id' => 'required|integer',
            'cantidad_goles' => 'required|integer|min_length[1]',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Actualizar el gol
        $golesModel->update($id, [
            'jugador_id' => $this->request->getPost('jugador_id'),
            'jornada_id' => $this->request->getPost('jornada_id'),
            'cantidad_goles' => $this->request->getPost('cantidad_goles')
        ]);

        return redirect()->to('/goles')->with('success', 'Gol actualizado exitosamente.');
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
        $golesModel = new GolesModel();

        if ($golesModel->delete($id)) {
            return redirect()->to('/goles')->with('success', 'Gol eliminado exitosamente.');
        } else {
            return redirect()->to('/goles')->with('error', 'No se pudo eliminar el registro de gol.');
        }
    }
}
