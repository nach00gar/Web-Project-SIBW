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
            
            $scientists = queryByName($nombre);
        }
        else{
            if (isset($_POST['bio'])) { 
                $bio = $_POST['bio'];
                $scientists = queryByBio($bio);
            }
        }
        
    }

    $action = "gestorcientificos";
    $msg = "Atendiendo a su criterio de búsqueda se han encontrado los siguientes científicos.";
    if(sizeof($scientists) == 0){
        $msg = "No se han encontrado científicos con ese criterio de búsqueda.";
    }
    echo $twig->render('configuracion.twig', ['act' => $action, 'usuario' => $usuario, 'scientists' => $scientists, 'msg' => $msg]);

?>