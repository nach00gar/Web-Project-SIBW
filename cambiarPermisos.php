<?php
    require_once "vendor/autoload.php"; 
    include("queryingdb.php"); 
    
    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    session_start();
    $usuario = array();
    if (isset($_SESSION['user'])) {
        $usuario = getUsuario($_SESSION['user']);
        
    }
    $action = "permisos";
    
    if( $usuario['tipo'] == "SUPER" ){ //RESTRINGIR esta opcion solo a usuarios SUPER
        $id = $_GET['id'];
        $tipo = $_POST['permiso'];
        grantPermission($id, $tipo);
        $msg = "Permisos actualizados satisfactoriamente!";
    }
    else{
        $msg = "ERROR, no eres superusuario!";
    }
    

    echo $twig->render('configuracion.twig', ['act' => $action, 'usuario' => $usuario, 'msg' => $msg ]);


?>