<?php
  
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("queryingdb.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  session_start();
  $usuario = array();
  if (isset($_SESSION['user'])) {
      $usuario = getUsuario($_SESSION['user']);
      
  }
  
  $scientist = array('name' => "ERROR404", 'fechasnm' => 'ninguna',  'content' => "No encontrado", 'idScientist' => "-1");
  $foto = array('direccion' => 'img/1.jpg');
  $comentarios = array();

  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $mysqli = conectar();

    $scientist = getScientist($id, $mysqli);

    $hashtags = getTags($id);

    $fotos = getFotos($id, $mysqli);

    $comentarios = getComentarios($id, $mysqli);
  } else {
    $id = -1;
  }

  echo $twig->render('cientifico.twig', ['scientist' => $scientist, 'foto' => $foto, 'comentarios' => $comentarios, 'fotos' => $fotos, 'usuario' => $usuario, 'hashtags' => $hashtags]);
?>