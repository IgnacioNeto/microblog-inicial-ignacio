<?php

// Para testar a segurança digitar na URL (Navegador): 
// http://localhost/microblog-inicial-ignacio/admin/categoria-exclui.php?id=2

use Microblog\ControleDeAcesso;


require_once "../vendor/autoload.php";

// Para proteger a página
$sessao = new ControleDeAcesso;
$sessao->verificaAcessoAdmin();
$sessao->verificaAcesso();