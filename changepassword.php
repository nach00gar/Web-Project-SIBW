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

    
    $action = "misDatos";
    
    $antigua = $_POST['old'];
    $nueva = $_POST['new1'];
    $check = $_POST['new2'];

    
    if(password_verify($antigua, $usuario['pass'])){
        if($nueva === $check){
            changePass($usuario['username'], $nueva);
            $msg = "La contraseña se ha cambiado correctamente";
        }
        else{
            $msg = "ERROR, los campos no coinciden";
        }

    }
    else{
        $msg = "ERROR, la contraseña antigua no es correcta";
    }

    

    echo $twig->render('configuracion.twig', ['act' => $action, 'usuario' => $usuario, 'msg' => $msg ]);
    
    
?>