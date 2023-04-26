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
  $comentarios = array();

  

  if (isset($_GET['id'])) {
    
    $id = $_GET['id'];
    $mysqli = conectar();

    $scientist = getScientist($id, $mysqli);

    $fotos = getFotos($id, $mysqli);

    addComment($id, $_REQUEST['msg'], $mysqli, $usuario);

    $comentarios = getComentarios($id, $mysqli);
  } else {
    $id = -1;
  }

  echo $twig->render('cientifico.twig', ['scientist' => $scientist, 'comentarios' => $comentarios, 'fotos' => $fotos, 'usuario' => $usuario]);
?>