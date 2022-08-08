<?php
 namespace Microblog;
 
 abstract class Utilitarios {

    // public static function dump(array | bool $dados)
    public static function dump($dados) {
        echo "<pre>";
        var_dump($dados);
        echo "</pre>";
    }
    
    public static function data($dados) {
        return date('d/m/Y H:i', strtotime($dados));
    }
}