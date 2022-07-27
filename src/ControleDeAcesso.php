<?php
namespace Microblog;

final class ControleDeAcesso {

    public function __construct()
    {
        // Se NÃO EXISTIR uma sessão em funcionamento
        if(!isset($_SESSION) ){
            // Então iniciamos a sessão
            session_start();
        }
    }

    public function verificaAcesso():void {
        // Se NÃO EXISTIR uma variável de sessão relacionada ao id do usuário logado...
        if(!isset($_SESSION['id']) ){
        /* Então siginifica que o usuário não está logado, portanto apague
           qualquer resquicio da sessão e force o usuário a ir para login.php */
           session_destroy();
           header("location:../login.php?acesso_proibido");
           die(); // exit
        }

    }

}