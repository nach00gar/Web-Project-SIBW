<?php
  
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("queryingdb.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  

  $mysqli = conectar();
  $assets = getAssets($mysqli);

  session_start();

  $usuario = array();
  if (isset($_SESSION['user'])) {
      $usuario = getUsuario($_SESSION['user']);
      
  }

  echo $twig->render('portada.twig', ['assets' => $assets, 'usuario' => $usuario]);
  
?>