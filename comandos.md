## Modelagem física

### Criar banco de dados
```sql
CREATE DATABASE microblog_ignacio CHARACTER SET utf8mb4;
```
<!-- ____________________________________________________________________ -->
### Criar tabela usuarios
```sql
CREATE TABLE usuarios(
    id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(45) NOT NULL,
    email VARCHAR(45) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin','editor') NOT NULL
);
```
<!-- ____________________________________________________________________ -->
### Criar tabela noticias
```sql
CREATE TABLE noticias(
    id MEDIUMINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    data DATETIME CURRENT_TIMESTAMP NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    texto TEXT NOT NULL,
    resumo TINYTEXT,
    imagem VARCHAR(45) NOT NULL,
    destaque ENUM('sim','não') NOT NULL,
    usuarios_id SMALLINT NOT NULL,
    categorias_id TINYINT NOT NULL

);
```
<!-- ____________________________________________________________________ -->
### Criar tabela categorias
```sql
CREATE TABLE categorias(
    id SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(45) NOT NULL

);
```
<!-- ____________________________________________________________________ -->
### Criação da chave estrangeira (relacionamento entre as tabelas)

```sql
-- Criação das chaves estrangeiras (Exercício)
ALTER TABLE usuarios 
    ADD CONSTRAINT fk_noticias_usuarios
    FOREIGN KEY (professor_id) REFERENCES professores(id);