<?php

// Para testar a segurança digitar na URL (Navegador): 
// http://localhost/microblog-inicial-ignacio/admin/categoria-exclui.php?id=2

use Microblog\ControleDeAcesso;
use Microblog\Categoria;

require_once "../vendor/autoload.php";

// Para proteger a página
$sessao = new ControleDeAcesso;
$sessao->verificaAcessoAdmin();
$sessao->verificaAcesso();

// Criamos um objeto para poder acessar os recursos da classe
$categoria = new Categoria; // Não esqueça do autoload e do namespace

// Obtemos o ID da url e o passamos para o setter
$categoria->setId($_GET['id']);

// Só então executamos o método de exclusão
$categoria->excluirCategoria();

// Após excluir, redirecionamos para a página de lista de categorias
header("location:Categorias.php");

// A idéia aqui é excluir direto (sem mensagens)