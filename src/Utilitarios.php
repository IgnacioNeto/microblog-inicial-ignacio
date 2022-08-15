<?php
 namespace Microblog;
 
 abstract class Utilitarios {

    // public static function dump(array | bool $dados)
    public static function dump($dados) {
        echo "<pre>";
        var_dump($dados);
        echo "</pre>";
    }

    public static function formataData($dados) {
        return date('d/m/Y H:i', strtotime($dados));
    }

    public static function limitaCaractere($dados) {
        return mb_strimwidth ($dados, 0 , 20, "...");
    }
    public static function formataTexto(string $texto):string {
        return nl2br($texto);
        // CKEditor - Opção para trazer recursos de formatação ao texto
    }
}