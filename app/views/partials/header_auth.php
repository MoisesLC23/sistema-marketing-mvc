<!DOCTYPE html>
<html lang="es">
<head>
<link rel="icon" type="image/png" href="/assets/img/conecta.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= htmlspecialchars($titulo ?? 'Acceso') ?></title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

  <!-- Estilos personalizados -->
  <style>
    body {
      background: url('/assets/img/login-bg.jpg') no-repeat center center fixed;
      background-size: cover;
      backdrop-filter: blur(2px);
    }
    .auth-card {
      background: rgba(255,255,255,0.88);
      border-radius: 12px;
    }
  </style>
</head>

<body>
