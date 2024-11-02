<?php
require_once 'conexion.php';
require_once 'jwt.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["user"]) && isset($_GET["pass"])) {
        $c = conexion();
        
        // Consulta solo por el nombre de usuario
        $s = $c->prepare("SELECT * FROM users WHERE user_app = :user");
        $s->bindValue(":user", $_GET["user"]);
        $s->execute();
        $s->setFetchMode(PDO::FETCH_ASSOC);
        $r = $s->fetch();
        
        if ($r) {
            // Verifica la contraseña usando password_verify
            if (password_verify($_GET["pass"], $r['user_password'])) {
                $t = JWT::create(["user_app" => $_GET["user"]], Config::SECRET);
                $result = ["login" => "y", "token" => $t];
            } else {
                $result = ["login" => "n", "token" => "Error", "message" => "Contraseña incorrecta"];
            }
        } else {
            $result = ["login" => "n", "token" => "Error", "message" => "Usuario no encontrado"];
        }

        header("http/1.1 200 ok");
        echo json_encode($result);
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(["login" => "n", "token" => "Error", "message" => "Parámetros inválidos"]);
    }
}
/*
require_once 'conexion.php';
require_once 'jwt.php';

if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(isset($_GET["user"]) && isset($_GET["pass"])){
        $c = conexion();
        $s = $c->prepare("SELECT * FROM users WHERE user_app = :user AND user_password = :pass");
        $s->bindValue(":user", $_GET["user"]);
        $s->bindValue(":pass", sha1($_GET["pass"]));
        $s->execute();
        $s->setFetchMode(PDO::FETCH_ASSOC);
        $r = $s->fetch();
        if($r){
            $t = JWT::create(["user_app" => $_GET["user"]], Config::SECRET);
            $result = ["login" => "y", "token" => $t];
        }else{
            $result = array("login" => "n", "token" => "Error");
        }
        header("http/1.1 200 ok");
        echo json_encode($result);
    }
}*/