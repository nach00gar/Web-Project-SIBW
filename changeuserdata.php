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
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $old = $usuario['username'];

    
    $u = getUsuario($username);

    if(empty($u) | $username === $old){
        modifyUser($old, $username, $email);
        $msg = "Datos actualizados con éxito";
        $usuario['username'] = $username;
        $usuario['email'] = $email;
        $_SESSION['user'] = $username;
    }
    else{
        $msg = "ERROR, el nombre de usuario ya existe";
    }

    

    echo $twig->render('configuracion.twig', ['act' => $action, 'usuario' => $usuario, 'msg' => $msg ]);
    
    
?>