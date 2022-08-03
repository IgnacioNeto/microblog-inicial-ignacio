<?php
namespace Microblog;
use PDO, Exception;

final class Noticia {
    private int $id;
    private string $data;
    private string $titulo;
    private string $texto;
    private string $resumo;
    private string $imagem;
    private string $destaque;
    private string $categoriaId;

    /* Criando uma propriedade do tipo usuário, ou seja, a partir
    de uma classe que criamos anteriormente, com o objetivo de
    reutilizar recursos dela (getters/ setters, etc...) 
    
    Isto permitirá fazer uma ASSOCIAÇÃO entre as classes. */ 
    public Usuario $usuario;

    private PDO $conexao;

    // Função que cria o objeto (__construct)
    public function __construct()
    {
        /* No momento em que o objeto Noticia for instanciado nas páginas,
        aproveitamos para também instanciar um objeto Usuario e com isso
        acessar recuros desta classe */
        $this->usuario = new Usuario;

        /* Usado assim (abaixo) para evitar 2 conexões com o Banco 
        reaproveitando a conexão existente da classe Usuario
        Obs: Foi criado em Usuario 1 Getter adicional 
        (public function getConexao(): PDO) ) */

        // $this->conexao = Banco::conecta();
        $this->conexao = $this->usuario->getConexao();
    }

    public function inserir():void {
        $sql = "INSERT INTO noticias(titulo, texto, resumo, imagem, destaque, usuario_id, categoria_id)
        VALUES(:titulo, :texto, :resumo, :imagem, :destaque, :usuario_id, :categoria_id)";
    

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindParam(":titulo,", $this->titulo, PDO::PARAM_STR);
            $consulta->bindParam(":texto,", $this->texto, PDO::PARAM_STR);
            $consulta->bindParam(":resumo,", $this->resumo, PDO::PARAM_STR);
            $consulta->bindParam(":imagem,", $this->imagem, PDO::PARAM_STR);
            $consulta->bindParam(":destaque,", $this->destaque, PDO::PARAM_STR);
            $consulta->bindParam(":categoria_id,", $this->categoriaId, PDO::PARAM_INT);

            /* Aqui, primeiro chamamos o getter de ID a partir do objeto/classe
            de Usuario. E só depois atribuimos ele ao parâmetro :usuario_id
            usando para isso o bindValue. Obs: bindParam pode ser usado, mas
            há riscos de erro devido a forma como ele é executado pelo PHP. 
            Por isso, recomenda-se o uso de bindValue em situações como essa. */
            $consulta->bindValue(":usuario_id,", $this->usuario->getId(), PDO::PARAM_INT);

            $consulta->execute();

        } catch (Exception $erro){
            die("Erro: ".$erro->getMessage());
        }


    }
    
// ________________________________________________________________
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    }
// ________________________________________________________________

    public function getTitulo(): string
    {
        return $this->titulo;
    }

  
    public function setTitulo(string $titulo)
    {
        $this->titulo = filter_var($titulo, FILTER_SANITIZE_SPECIAL_CHARS);


    }
// ________________________________________________________________
 
    public function getTexto(): string
    {
        return $this->texto;
    }

 
    public function setTexto(string $texto)
    {
        $this->texto = filter_var($texto, FILTER_SANITIZE_SPECIAL_CHARS);


    }
// ________________________________________________________________

    public function getResumo(): string
    {
        return $this->resumo;
    }


    public function setResumo(string $resumo)
    {
        $this->resumo = filter_var($resumo, FILTER_SANITIZE_SPECIAL_CHARS);


    }
// ________________________________________________________________
 
    public function getImagem(): string
    {
        return $this->imagem;
    }


    public function setImagem(string $imagem)
    {
        $this->imagem = filter_var($imagem, FILTER_SANITIZE_SPECIAL_CHARS);


    }
// ________________________________________________________________
 
    public function getDestaque(): string
    {
        return $this->destaque;
    }

  
    public function setDestaque(string $destaque)
    {
        $this->destaque = filter_var($destaque, FILTER_SANITIZE_SPECIAL_CHARS);


    }
// ________________________________________________________________

    public function getCategoriaId(): string
    {
        return $this->categoriaId;
    }


    public function setCategoriaId(string $categoriaId)
    {
        $this->categoriaId = filter_var($categoriaId , FILTER_SANITIZE_NUMBER_INT);


    }
}
