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
            $consulta->bindParam(":titulo", $this->titulo, PDO::PARAM_STR);
            $consulta->bindParam(":texto", $this->texto, PDO::PARAM_STR);
            $consulta->bindParam(":resumo", $this->resumo, PDO::PARAM_STR);
            $consulta->bindParam(":imagem", $this->imagem, PDO::PARAM_STR);
            $consulta->bindParam(":destaque", $this->destaque, PDO::PARAM_STR);
            $consulta->bindParam(":categoria_id", $this->categoriaId, PDO::PARAM_INT);

            /* Aqui, primeiro chamamos o getter de ID a partir do objeto/classe
            de Usuario. E só depois atribuimos ele ao parâmetro :usuario_id
            usando para isso o bindValue. Obs: bindParam pode ser usado, mas
            há riscos de erro devido a forma como ele é executado pelo PHP. 
            Por isso, recomenda-se o uso de bindValue em situações como essa. */
            $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);

            $consulta->execute();

        } catch (Exception $erro){
            die("Erro: ".$erro->getMessage());
        }


    }

    public function upload(array $arquivo) {
        // Definindo os formatos aceitos
        $tiposValidos = [
            "image/png",
            "image/jpeg",
            "image/gif",
            "image/svg+xml"
        ];

    if(!in_array($arquivo['type'], $tiposValidos) ) {
            die("
            <script>
            alert('Formato inválido!');
            history.back();
            </script>"
            );

        // Teste
        // } else {
        //     die("<script>alert('Formato válido!');history.back();</script>");
        }

        // Acessando apenas o nome do arquivo
        $nome = $arquivo['name'];

        // Acessando os dados de acesso temporário
        $temporario = $arquivo['tmp_name'];

        // Definindo a pasta de destino junto com o nome do arquivo
        $destino = "../imagem/".$nome;

        /* Usamos a função abaixo para pegar da área temporária e
         enviar para a pasta de destino (com o nome do arquivo) */
        move_uploaded_file($temporario, $destino);
    }
    
    public function listar():array {
        // Se o tipo de usuário logado for admin
        if( $this->usuario->getTipo() === 'admin') {
            // Então ele poderá acessar as notícias de todo mundo
            $sql = "SELECT 
            noticias.id, noticias.titulo,
            noticias.data, noticias.destaque,
            usuarios.nome AS autor 
            FROM noticias LEFT JOIN usuarios /* Se usar INNER JOIN ao apagar o usuário a notícia não aparece, usar LEFT pois a noticia esta a esq. */
            ON noticias.usuario_id = usuarios.id
            ORDER BY data DESC";
            // Obs: noticias.usuario_id (FK- Foreign key) e usuarios.id (PK-Primary Key)

        } else {
            // Senão (ou seja, é editor), este usuário (editor)
            // poderá acessar SOMENTE suas próprias notícias
            $sql = "SELECT id, titulo, data, destaque FROM noticias WHERE usuario_id = :usuario_id ORDER BY data DESC";
        }
        try {
            $consulta = $this->conexao->prepare($sql);

            /* Se NÃO FOR um usuário admin, então trate o parâmetro de
             usuario trate o parâmetro de usuario_id antes de exexutar */
            if ($this->usuario->getTipo() !== 'admin') {
            $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
        }
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $erro){
            die("Erro: ".$erro->getMessage());
        }
        
        return $resultado;

    } // Final do listar

    public function listarUm():array {
        
        if( $this->usuario->getTipo() === 'admin') {
            
            $sql = "SELECT
            titulo, texto, resumo, imagem, usuario_id, categoria_id, destaque
            FROM noticias WHERE id = :id";

        } else {

            $sql = "SELECT
            titulo, texto, resumo, imagem, usuario_id, categoria_id, destaque
            FROM noticias
            WHERE id = :id AND usuario_id = :usuario_id";
        }
        try {
            $consulta = $this->conexao->prepare($sql);
            // parametro id da noticia

            $consulta->bindParam(
                ":id",
                $this->id,
                PDO::PARAM_INT);

            if ($this->usuario->getTipo() !== 'admin') {
    
            // parametro usuario_id
            $consulta->bindValue(
                ":usuario_id",
                $this->usuario->getId(),
                PDO::PARAM_INT);
        }
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $erro){
            die("Erro: ".$erro->getMessage());
        }
        
        return $resultado;

    } // Final do listarUm

    public function atualizar():void {
        
        if( $this->usuario->getTipo() === 'admin') {
            
            $sql = "UPDATE noticias SET
                   titulo = :titulo, texto = :texto,
                   resumo = :resumo, imagem = :imagem,
                   categoria_id = :categoria_id,
                   destaque = :destaque WHERE id = :id";

        } else {

            $sql = "UPDATE noticias SET
            titulo = :titulo, texto = :texto,
            resumo = :resumo, imagem = :imagem,
            categoria_id = :categoria_id,
            destaque = :destaque WHERE id = :id
            AND usuario_id = usuario_id";
        }
        try {
            $consulta = $this->conexao->prepare($sql);
            // parametro id da noticia

            $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);
            $consulta->bindParam(":titulo", $this->titulo, PDO::PARAM_STR);
            $consulta->bindParam(":texto", $this->texto, PDO::PARAM_STR);
            $consulta->bindParam(":resumo", $this->resumo, PDO::PARAM_STR);
            $consulta->bindParam(":imagem", $this->imagem, PDO::PARAM_STR);
            $consulta->bindParam(":categoria_id", $this->categoriaId, PDO::PARAM_INT);
            $consulta->bindParam(":destaque", $this->destaque, PDO::PARAM_STR);

            if ($this->usuario->getTipo() !== 'admin') {
    
            // parametro usuario_id
            $consulta->bindValue(
                ":usuario_id",
                $this->usuario->getId(),
                PDO::PARAM_INT);
        }
        $consulta->execute();
        

        } catch (Exception $erro){
            die("Erro: ".$erro->getMessage());
        }
        


    } // Final do atualizar

// __________________________________________________

public function excluirNoticia():void {
    if($this->usuario->getTipo() === 'admin') {
        // O administrador pode apagar qualquer notícia
        $sql = "DELETE FROM noticias WHERE id = :id ";

    } else {
        // O editor só pode apagar as notícias dele mesmo
        $sql = "DELETE FROM noticias WHERE id = :id AND usuario_id = :usuario_id";
    }

    try {
        $consulta = $this->conexao->prepare($sql);
        $consulta->bindParam(':id', $this->id, PDO::PARAM_INT);
        if ($this->usuario->getTipo() !== 'admin') {
            $consulta->bindValue(':usuario_id', $this->usuario->getId(), PDO::PARAM_INT);
        }

        $consulta->execute();

    } catch (Exception $erro){
        die("Erro: ".$erro->getMessage());
    }
}
// ________________________________________________________________
// Métodos para a área publica do site

public function listarDestaques():array {
    $sql = "SELECT titulo, imagem, resumo, id FROM noticias
    WHERE destaque = :destaque ORDER BY data DESC";

    try {
        $consulta = $this->conexao->prepare($sql);
        $consulta->bindParam(':destaque', $this->destaque, PDO::PARAM_STR);
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $erro){
        die("Erro: ".$erro->getMessage());
    }
    return $resultado;
}

public function listarTodas():array {
    $sql = "SELECT data, titulo, resumo, id FROM noticias
    ORDER BY data DESC";

    try {
        $consulta = $this->conexao->prepare($sql);
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $erro){
        die("Erro: ".$erro->getMessage());
    }
    return $resultado;
}

public function listarDetalhes():array {
    $sql = "SELECT
    noticias.id, noticias.titulo, noticias.data,
    noticias.texto, noticias.imagem,
    usuarios.nome AS autor
    FROM noticias LEFT JOIN usuarios
    ON noticias.usuario_id = usuarios.id
    WHERE noticias.id = :id";
    
try {
    $consulta = $this->conexao->prepare($sql);
    $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);
    $consulta->execute();
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

} catch (Exception $erro){
    die("Erro: ".$erro->getMessage());
}
return $resultado;



}

public function listarPorCategoria():array {
    $sql = "SELECT
        noticias.id, noticias.titulo,
        noticias.data, noticias.resumo,
        usuarios.nome AS autor,
        categorias.nome AS categoria
    FROM noticias 
        LEFT JOIN usuarios ON noticias.usuario_id = usuarios.id
        INNER JOIN categorias ON noticias.categoria_id = categorias.id
    WHERE noticias.categoria_id = :categoria_id";
    
try {
    $consulta = $this->conexao->prepare($sql);
    $consulta->bindParam(":categoria_id", $this->categoriaId, PDO::PARAM_INT);
    $consulta->execute();
    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $erro){
    die("Erro: ".$erro->getMessage());
}
return $resultado;


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
