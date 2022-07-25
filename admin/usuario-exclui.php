<?php

use Microblog\Usuario;

require_once "../vendor/autoload.php";

$usuario = new Usuario;
$usuario->setId($_GET['id']);
$usuario->excluirUsuario();


    header("location:usuarios.php");
    // A idéia aqui é excluir direto (sem mensagens)