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
        $id = -1;
        if (isset($_POST['id'])) { 
            $id = $_POST['id'];
            deleteScientist($id);
        }
        else{
            if (isset($_GET['id'])) { 
                $id = $_GET['id'];
                deleteScientist($id);
            }
        }
        
    }
    
    header("Location: configuracion.php?act=gestorcientificos");

?>