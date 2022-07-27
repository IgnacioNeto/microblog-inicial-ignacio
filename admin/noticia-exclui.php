<?php

use Microblog\ControleDeAcesso;

require_once "../vendor/autoload.php";

// Para proteger a página
$sessao = new ControleDeAcesso;
$sessao->verificaAcesso();