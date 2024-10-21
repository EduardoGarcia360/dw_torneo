<?php

namespace App\Controllers;

use App\Models\UsuariosModel;

class Home extends BaseController
{
    public function index(): string
    {
        $mensaje = session('mensaje');
        return view('login', ['mensaje' => $mensaje]);
    }

    public function inicio(): string
    {
        return view('inicio');
    }
    public function login()
    {
        $correo = $this->request->getPost('correoElectronico');
        $password = $this->request->getPost('contrasena');
        $usuarioModel = new UsuariosModel();
        $datosUsuario = $usuarioModel->verificarUsuario($correo, $password);
        if (!$datosUsuario) {
            return redirect()->to(base_url('/'))->with('mensaje', '0');
        } else {
            $data = [
                'id' => $datosUsuario['id'],
                'nombre' => $datosUsuario['nombre'],
                'apellido' => $datosUsuario['apellido'],
            ];
            $session = session();
            $session->set($data);
            return redirect()->to(base_url('/inicio'))->with('mensaje', '1');
        }
    }
    public function salir()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('/'));
    }
}
