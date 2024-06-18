/*
CREATE TABLE pessoas (
	id_pessoa INT PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(100),
	cpfcnpj VARCHAR(20),
	rgie VARCHAR(20),
	nasc DATE,
	tipopessoa smallint		
);

CREATE TABLE usuarios(
	id_usuario INT PRIMARY KEY AUTO_INCREMENT,
	id_pessoa INT,
	nome VARCHAR(100),
	email VARCHAR(100),
	senha VARCHAR(100)clientecliente
); 

CREATE TABLE estoque(
	id_produto int PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(100),
	id_fornec INT,
	quant DOUBLE,
	id_grupo INT,
	id_marca INT 
);

CREATE TABLE marca(
	id_marca INT PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(100)
);

CREATE TABLE grupo(
	id_grupo INT PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(100)
);

CREATE TABLE venda(
	id_venda INT PRIMARY KEY AUTO_INCREMENT,
	id_cliente INT,
	id_vendedor INT,
	tipovenda SMALLINT,
	total double
);

CREATE TABLE itensvenda(
	id_item INT PRIMARY KEY AUTO_INCREMENT,
	id_venda INT,
	nome VARCHAR(100),
	quant DOUBLE,
	precounid DOUBLE,
	precototal DOUBLE 
);
*/
SELECT * FROM pessoas WHERE id_pessoa = 2


usuarios