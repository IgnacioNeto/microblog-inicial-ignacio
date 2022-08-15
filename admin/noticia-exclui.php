<?php

use Microblog\Noticia;
use Microblog\ControleDeAcesso;

require_once "../vendor/autoload.php";

// Para proteger a página
$sessao = new ControleDeAcesso;
$sessao->verificaAcesso();

// Criamos um objeto para poder acessar os recursos da classe
$noticia = new Noticia; // Não esqueça do autoload e do namespace (Objeto da noticia)

// Obtemos o ID da url e o passamos para o setter
$noticia->setId($_GET['id']);

$noticia->usuario->setId($_SESSION['id']);
$noticia->usuario->setTipo($_SESSION['tipo']);

// Só então executamos o método de exclusão
$noticia->excluirNoticia();

// Após excluir, redirecionamos para a página de lista de usuários
header("location:noticias.php");

// A idéia aqui é excluir direto (sem mensagens)