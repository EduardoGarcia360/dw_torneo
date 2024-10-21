<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class Usuarios extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $usuarioModel = new UsuariosModel();
        $data['usuarios'] = $usuarioModel->findAll(); // Obtener todos los usuarios

        return view('usuarios/index', $data); // Cargar la vista de index
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
        return view('usuarios/nuevo'); // Mostrar la vista para crear un nuevo usuario
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $usuarioModel = new UsuariosModel();

        // Validación de los datos del formulario
        if (!$this->validate([
            'nombre' => 'required|min_length[3]|max_length[45]',
            'apellido' => 'required|min_length[3]|max_length[45]',
            'correoElectronico' => 'required|valid_email|max_length[75]|is_unique[usuarios.correoElectronico]',
            'telefono' => 'required|max_length[45]',
            'contrasena' => 'required|min_length[6]|max_length[45]',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Guardar el nuevo usuario
        $usuarioModel->save([
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'correoElectronico' => $this->request->getPost('correoElectronico'),
            'telefono' => $this->request->getPost('telefono'),
            'contrasena' => $this->request->getPost('contrasena'), // Hash de contraseña
        ]);

        return redirect()->to('/usuarios')->with('success', 'Usuario creado exitosamente.');
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
        $usuarioModel = new UsuariosModel();
        $data['usuario'] = $usuarioModel->find($id);

        if (!$data['usuario']) {
            return redirect()->to('/usuarios')->with('error', 'Usuario no encontrado.');
        }

        return view('usuarios/editar', $data); // Cargar la vista de edición
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
        $usuarioModel = new UsuariosModel();

        // Validación de los datos del formulario
        if (!$this->validate([
            'nombre' => 'required|min_length[3]|max_length[45]',
            'apellido' => 'required|min_length[3]|max_length[45]',
            'correoElectronico' => "required|valid_email|max_length[75]|is_unique[usuarios.correoElectronico,id,{$id}]",
            'telefono' => 'required|max_length[45]',
            'contrasena' => 'required|min_length[6]|max_length[45]',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Actualizar los datos del usuario
        $usuarioModel->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'correoElectronico' => $this->request->getPost('correoElectronico'),
            'telefono' => $this->request->getPost('telefono'),
            'contrasena' => $this->request->getPost('contrasena'), // Hash de contraseña
        ]);

        return redirect()->to('/usuarios')->with('success', 'Usuario actualizado exitosamente.');
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
        $usuarioModel = new UsuariosModel();
        
        if ($usuarioModel->delete($id)) {
            return redirect()->to('/usuarios')->with('success', 'Usuario eliminado exitosamente.');
        } else {
            return redirect()->to('/usuarios')->with('error', 'No se pudo eliminar el usuario.');
        }
    }
}
