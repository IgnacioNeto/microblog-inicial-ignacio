<?php
namespace Microblog;
use PDO, Exception;

// final class (Permite que a classe não gere herança)
final class  Usuario {
    private int $id;
    private string $nome;
    private string $email;
    private string $senha;
    private string $tipo;
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = Banco::conecta();
    }
    
    public function listar():array {
        $sql = "SELECT id, nome, email, tipo FROM usuarios ORDER BY nome";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $erro){
            die("Erro: ".$erro->getMessage());
        }
        return $resultado;
    }
// _________________________________________________________________________________
    public function inserir():void {
        $sql = "INSERT INTO usuarios(nome, email, senha, tipo)
        VALUES(:nome, :email, :senha, :tipo)";

    try {
        $consulta = $this->conexao->prepare($sql);
        $consulta->bindParam(":nome", $this->nome, PDO::PARAM_STR);
        $consulta->bindParam(":email", $this->email, PDO::PARAM_STR);
        $consulta->bindParam(":senha", $this->senha, PDO::PARAM_STR);
        $consulta->bindParam(":tipo", $this->tipo, PDO::PARAM_STR);
        $consulta->execute();

    } catch (Exception $erro){
        die("Erro: ".$erro->getMessage());
    }
}
// _________________________________________________________________________________
    public function listarUm():array {
        $sql = "SELECT * FROM usuarios WHERE id = :id";

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
// _________________________________________________________________________________
    public function atualizar():void {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha, tipo = :tipo WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindParam(":nome", $this->nome, PDO::PARAM_STR);
            $consulta->bindParam(":email", $this->email, PDO::PARAM_STR);
            $consulta->bindParam(":senha", $this->senha, PDO::PARAM_STR);
            $consulta->bindParam(":tipo", $this->tipo, PDO::PARAM_STR);
            $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);
            $consulta->execute();
    
        } catch (Exception $erro){
            die("Erro: ".$erro->getMessage());
        }
    }

// _________________________________________________________________________________

public function codificaSenha(string $senha):string {
    return password_hash($senha, PASSWORD_DEFAULT); // Algoritimo de cryptografia escolhido (PASSWORD_DEFAULT)
}
// _________________________________________________________________________________
// Usamos a "password_verify" para comparar as 2 senhas.
public function verificaSenha(string $senhaFormulario, string $senhaBanco):string {
    if ( password_verify($senhaFormulario, $senhaBanco) ) {
        // Se forem iguais, mantemos a senha existente no banco
        return $senhaBanco;
    } else {
        // Se forem diferentes, então codificamos esta nova senha
        return $this->codificaSenha($senhaFormulario);
    }

}

// __________________________________________________

public function excluirUsuario():void {
    $sql = "DELETE FROM usuarios WHERE id = :id ";
    try {
        $consulta = $this->conexao->prepare($sql);
        $consulta->bindParam(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();

    } catch (Exception $erro){
        die("Erro: ".$erro->getMessage());
    }
}
// __________________________________________________
// Buscando array ou boolean (quando achar ou não o usuario)
public function buscar():array | bool {
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    try {
        $consulta = $this->conexao->prepare($sql);
        $consulta->bindParam(':email', $this->email, PDO::PARAM_STR);
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

    } catch (Exception $erro){
        die("Erro: ".$erro->getMessage());
    }
    return $resultado;
}

// _________________________________________________________________________________


// Getters e Setters c/ filtros de sanitização
// ________________________________________________

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    }
// ________________________________________________

    public function getNome(): string
    {
        return $this->nome;
    }


    public function setNome(string $nome)
    {
        $this->nome = filter_var($nome, FILTER_SANITIZE_SPECIAL_CHARS);

    }
// ________________________________________________

    public function getEmail(): string
    {
        return $this->email;
    }


    public function setEmail(string $email)
    {
        $this->email = filter_var($email, FILTER_SANITIZE_EMAIL);

    }

// ________________________________________________
    public function getSenha(): string
    {
        return $this->senha;
    }


    public function setSenha(string $senha)
    {
        $this->senha = filter_var($senha, FILTER_SANITIZE_SPECIAL_CHARS);

    }

// ________________________________________________
    public function getTipo(): string
    {
        return $this->tipo;
    }


    public function setTipo(string $tipo)
    {
        $this->tipo = filter_var($tipo, FILTER_SANITIZE_SPECIAL_CHARS);

    }
// ________________________________________________
    public function getConexao(): PDO
    {
        return $this->conexao;
    }
}

