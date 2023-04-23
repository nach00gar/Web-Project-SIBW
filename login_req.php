<?php
    require_once "vendor/autoload.php"; 
    include("queryingdb.php"); 
    
    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    if (isset($_GET['act'])){
        $action = $_GET['act'];
        
        if ($action == "login"){ //Login
            $username = $_POST['username'];
            $pass = $_POST['pass'];

            if(checkLogin($username, $pass)){
                session_start();
                $_SESSION['user'] = $username;
                header("Location: portada.php"); 
            }
            else{
                $msg = "Error de login, intentalo de nuevo!";
                echo $twig->render('loginpage.twig', ['act' => $action, 'msg' => $msg]);
            }

            
        }
        elseif ($action == "signIn") { //Registro
            
            $username = $_POST['username'];
            $pass = $_POST['pass'];
            $email = $_POST['email'];

            addUser($username, $pass, $email);

            $action ="login";
            $msg = "Ahora inicia sesión para usar el servicio!";
            echo $twig->render('loginpage.twig', ['act' => $action, 'msg' => $msg]);
        }
    }
    else { //ERROR, vuelve a la pagina principal
        $action = "login";
        echo $twig->render('loginpage.twig', ['act' => $action]);
    }



    
    
    
    
    
?>