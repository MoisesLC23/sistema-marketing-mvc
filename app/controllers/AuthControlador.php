<?php

require_once __DIR__ . '/../middleware/csrf.php';

require_once __DIR__ . '/../models/Usuario.php';



class AuthControlador {



  private $usuarioModel;

  public function __construct($pdo) {

    $this->usuarioModel = new Usuario($pdo);

  }



  public function login() {

    if (!empty($_SESSION['usuario_id'])) {

      header("Location: /marketing/public/dashboard");

      exit;

    }



    if ($_SERVER['REQUEST_METHOD'] === 'POST') {



      if (!csrf_check($_POST['csrf'] ?? '')) {

        http_response_code(400);

        $error = "Sesión expirada, vuelve a intentar.";

        include __DIR__ . '/../views/auth/login.php';

        return;

      }



      $correo = trim($_POST['correo'] ?? '');

      $clave  = trim($_POST['contrasena'] ?? '');



      if ($correo === '' || $clave === '') {

        $error = "Por favor, completa todos los campos.";

        include __DIR__ . '/../views/auth/login.php';

        return;

      }



      $usuario = $this->usuarioModel->buscarPorCorreoLogin($correo);



      if ($usuario && password_verify($clave, $usuario['contraseña_hash'])) {



        $rolId = (int)$usuario['rol_id'];

        $rolTexto = ($rolId === 1) ? 'admin' : 'empleado_marketing';



        $_SESSION['usuario_id']      = $usuario['id'];

        $_SESSION['nombre']          = $usuario['nombre'];

        $_SESSION['rol']             = $rolId;

        $_SESSION['usuario_rol_id']  = $rolId;

        $_SESSION['usuario_rol']     = $rolTexto;



        $_SESSION['usuario'] = [

          'id'      => $usuario['id'],

          'nombre'  => $usuario['nombre'],

          'rol_id'  => $rolId,

          'correo'  => $usuario['correo'],

        ];



        header("Location: /marketing/public/dashboard");

        exit;

      } else {

        $error = "Correo o contraseña incorrectos.";

        include __DIR__ . '/../views/auth/login.php';

        return;

      }

    }



    include __DIR__ . '/../views/auth/login.php';

  }



  public function logout() {

    $_SESSION = [];

    if (session_status() === PHP_SESSION_ACTIVE) {

      session_destroy();

    }

    header("Location: /marketing/public/login");

    exit;

  }

}