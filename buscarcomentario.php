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
    
    
    if( $usuario['tipo'] == "SUPER" or $usuario['tipo'] == "GESTOR" ){ //RESTRINGIR esta opcion solo a usuarios SUPER o GESTOR
        if (isset($_POST['nombre'])) { 
            $nombre = $_POST['nombre'];
            
            $comentarios = queryComments($nombre);
        }
        
    }

    $action = "comentarios";
    $msg = "Atendiendo a su criterio de búsqueda se han encontrado los siguientes comentarios.";
    if(sizeof($comentarios) == 0){
        $msg = "No se han encontrado comentarios en la búsqueda.";
    }
    $scientists = getAssets();
    echo $twig->render('configuracion.twig', ['act' => $action, 'usuario' => $usuario, 'scientists' => $scientists, 'comentarios' => $comentarios, 'msg' => $msg]);
?>