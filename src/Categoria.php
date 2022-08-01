<?php
namespace Microblog;
use PDO, Exception;

// final class (Permite que a classe não gere herança)
final class  Categoria {
    private int $id;
    private string $nome;
    private PDO $conexao;

    // Função que cria o objeto (__construct)
    public function __construct()
    {
        $this->conexao = Banco::conecta();
    }

    public function listar():array {
        $sql = "SELECT id, nome FROM categorias ORDER BY nome";

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
    $sql = "INSERT INTO categorias(nome)
    VALUES(:nome)";

try {
    $consulta = $this->conexao->prepare($sql);
    $consulta->bindParam(":nome", $this->nome, PDO::PARAM_STR);
    $consulta->execute();

    } catch (Exception $erro){
        die("Erro: ".$erro->getMessage());
    }
}
// _________________________________________________________________________________
public function listarUm():array {
    $sql = "SELECT * FROM categorias WHERE id = :id";

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
    $sql = "UPDATE categorias SET nome = :nome WHERE id = :id";

    try {
        $consulta = $this->conexao->prepare($sql);
        $consulta->bindParam(":nome", $this->nome, PDO::PARAM_STR);
        $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);
        $consulta->execute();

    } catch (Exception $erro){
        die("Erro: ".$erro->getMessage());
    }
}
// __________________________________________________

public function excluirCategoria():void {
    $sql = "DELETE FROM categorias WHERE id = :id ";
    try {
        $consulta = $this->conexao->prepare($sql);
        $consulta->bindParam(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();

    } catch (Exception $erro){
        die("Erro: ".$erro->getMessage());
    }
}

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

}