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
    public function login(int $id, string $nome, string $tipo):void{
        /* No momento que ocorrer o login, adicionamos à sessão variáveis de sessão
         contendo os dados necessários para o sistema */
        $_SESSION['id'] = $id;
        $_SESSION['nome'] = $nome;
        $_SESSION['tipo'] = $tipo;

    }
    public function logout():void{
        /* Logout */
         session_start();
         session_destroy();
         header("location:../login.php?logout");
         die(); // exit

    }

}