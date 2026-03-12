<?php

return [
  'db' => [
    'host'    => 'TU_HOST_AQUI',       
    'name'    => 'NOMBRE_DE_TU_BD', 
    'user'    => 'TU_USUARIO_AQUI',
    'pass'    => 'TU_CONTRASEÑA_AQUI', 
    'charset' => 'utf8mb4'
  ],

  'app' => [
    'base_url'     => 'http://localhost/marketing/public',
    'session_name' => 'conecta_sess',
    'csrf_key'     => 'TU_CLAVE_CSRF_AQUI'
  ],

  'openai' => [
    'api_key' => 'TU_API_KEY_DE_OPENAI_AQUI', 
    'model'   => 'gpt-4.1-mini'
  ]
];