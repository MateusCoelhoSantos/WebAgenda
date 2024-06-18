-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.30 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para webagenda
CREATE DATABASE IF NOT EXISTS `webagenda` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `webagenda`;

-- Copiando estrutura para tabela webagenda.contato
CREATE TABLE IF NOT EXISTS `contato` (
  `id_contato` int NOT NULL AUTO_INCREMENT,
  `id_pessoa` int DEFAULT NULL,
  `tipocontato` smallint DEFAULT NULL,
  `contato` varchar(50) DEFAULT NULL,
  `tipo` smallint DEFAULT NULL,
  PRIMARY KEY (`id_contato`),
  KEY `FK_contato_pessoas` (`id_pessoa`),
  CONSTRAINT `FK_contato_pessoas` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoas` (`id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.contato: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela webagenda.endereco
CREATE TABLE IF NOT EXISTS `endereco` (
  `id_endereco` int unsigned NOT NULL AUTO_INCREMENT,
  `id_pessoa` int DEFAULT NULL,
  `rua` varchar(100) DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `uf` char(50) DEFAULT NULL,
  `cep` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_endereco`),
  KEY `FK__endereco_pessoa` (`id_pessoa`),
  CONSTRAINT `FK__endereco_pessoa` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoas` (`id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.endereco: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela webagenda.estoque
CREATE TABLE IF NOT EXISTS `estoque` (
  `id_produto` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `id_fornec` int DEFAULT NULL,
  `quant` double DEFAULT NULL,
  `id_grupo` int DEFAULT NULL,
  `id_marca` int DEFAULT NULL,
  PRIMARY KEY (`id_produto`),
  KEY `FK_estoque_pessoas` (`id_fornec`),
  KEY `FK_estoque_grupo` (`id_grupo`),
  KEY `FK_estoque_marca` (`id_marca`),
  CONSTRAINT `FK_estoque_grupo` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`),
  CONSTRAINT `FK_estoque_marca` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`),
  CONSTRAINT `FK_estoque_pessoas` FOREIGN KEY (`id_fornec`) REFERENCES `pessoas` (`id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.estoque: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela webagenda.grupo
CREATE TABLE IF NOT EXISTS `grupo` (
  `id_grupo` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.grupo: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela webagenda.itensvenda
CREATE TABLE IF NOT EXISTS `itensvenda` (
  `id_item` int NOT NULL AUTO_INCREMENT,
  `id_venda` int DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `quant` double DEFAULT NULL,
  `precounid` double DEFAULT NULL,
  `precototal` double DEFAULT NULL,
  PRIMARY KEY (`id_item`),
  KEY `FK_itensvenda_venda` (`id_venda`),
  CONSTRAINT `FK_itensvenda_venda` FOREIGN KEY (`id_venda`) REFERENCES `venda` (`id_venda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.itensvenda: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela webagenda.marca
CREATE TABLE IF NOT EXISTS `marca` (
  `id_marca` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_marca`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.marca: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela webagenda.pessoas
CREATE TABLE IF NOT EXISTS `pessoas` (
  `id_pessoa` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cpfcnpj` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rgie` varchar(20) DEFAULT NULL,
  `nasc` date DEFAULT NULL,
  `f_j` smallint DEFAULT NULL COMMENT '0-Pessoa Fisica, 1-Pessoa Juridica',
  `tipopessoa` smallint DEFAULT NULL COMMENT '0-Usuario, 1-Cliente, 2-Medico',
  `email` varchar(50) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `orientacaosex` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'M-Masculino, F-Feminino, N-Não Identificado',
  PRIMARY KEY (`id_pessoa`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.pessoas: ~6 rows (aproximadamente)
INSERT IGNORE INTO `pessoas` (`id_pessoa`, `nome`, `cpfcnpj`, `rgie`, `nasc`, `f_j`, `tipopessoa`, `email`, `telefone`, `orientacaosex`) VALUES
	(1, 'Mateus Coelho', '02878024265', '0001', '2003-09-13', 0, 1, 'mateus@hotmail.com', '69992134628', 'M'),
	(2, 'Mateus Santos', '02878024265', '0001', '2007-09-13', 0, 1, 'Mateus@gmail.com', '69992235346', 'M'),
	(3, 'teste', '123', '123', '2004-05-07', 0, 1, 'teste@gmail.com', '123', 'M'),
	(4, 'Atualização', 'atual', 'atualizado', '2024-06-18', 0, 1, 'atualizado@atual.com', '1231231213', 'M'),
	(5, 'teste2', '123321', '321123', '2024-06-18', 0, 1, 'teste2@gmail.com', '123321', 'N'),
	(7, 'amanda amorim', '1234', '1234', '2024-05-31', 0, 1, 'teste', '123321', 'F'),
	(8, 'roberto renato', '123', '123', '2024-05-26', 0, 1, 'robren@hotmail.com', '431123', 'N'),
	(9, 'jose olivas', '567765', '43232', '2024-03-05', 0, 1, 'teste', 'teste', 'N'),
	(10, 'paulinho pereira', '1233254', '123657', '2023-09-06', 0, 1, 'pereira@paulinho.com', 'treqwe', 'M'),
	(11, 'atestado', 'amianto', 'eutanasia', '2024-02-16', 1, 1, 'jovem', 'magaiver', 'N'),
	(12, 'abraco', '099876543123', '123345', '2024-01-24', 1, 1, 'jovem', '123321', 'F'),
	(13, 'amanda amorim', '1234', '1234', '2004-02-13', 1, 1, 'atualizado@atual.com', '431123', 'F'),
	(14, 'jose olivas', '567765', '123657', '2024-05-26', 1, 1, 'jovem', '431123', 'M'),
	(15, 'amanda amorim', '1234', '43232', '2024-04-03', 1, 1, 'atualizado@atual.com', '1231231213', 'N');

-- Copiando estrutura para tabela webagenda.reservas
CREATE TABLE IF NOT EXISTS `reservas` (
  `id_reserva` int NOT NULL AUTO_INCREMENT,
  `id_pessoa` int NOT NULL DEFAULT '0',
  `tiporeserva` smallint DEFAULT '0',
  `id_venda` int DEFAULT '0',
  `horarioini` time DEFAULT '00:00:00',
  `horariofin` time DEFAULT '00:00:00',
  `quant` double DEFAULT '0',
  `valor` double DEFAULT '0',
  `finalizado` smallint DEFAULT '0',
  PRIMARY KEY (`id_reserva`),
  KEY `FK__pessoas` (`id_pessoa`),
  KEY `FK__venda` (`id_venda`),
  CONSTRAINT `FK__pessoas` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoas` (`id_pessoa`),
  CONSTRAINT `FK__venda` FOREIGN KEY (`id_venda`) REFERENCES `venda` (`id_venda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.reservas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela webagenda.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `id_pessoa` int DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `FK_usuarios_pessoas` (`id_pessoa`),
  CONSTRAINT `FK_usuarios_pessoas` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoas` (`id_pessoa`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.usuarios: ~4 rows (aproximadamente)
INSERT IGNORE INTO `usuarios` (`id_usuario`, `id_pessoa`, `nome`, `email`, `senha`, `telefone`) VALUES
	(1, NULL, '', 'mateuscoelhosoporo@gmail.com', 'mateus13', '69992134628'),
	(2, NULL, '', 'mateuscoelhosoporo@gmail.com', 'mateus13', '69992134628'),
	(3, NULL, '', 'mateuscoelhosoporo@gmail.com', 'mateus13', '69992134628'),
	(4, NULL, 'Mateus Coelho', 'mateuscoelhosoporo@gmail.com', 'mat', '69992134628');

-- Copiando estrutura para tabela webagenda.venda
CREATE TABLE IF NOT EXISTS `venda` (
  `id_venda` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `id_vendedor` int DEFAULT NULL,
  `tipovenda` smallint DEFAULT NULL,
  `total` double DEFAULT NULL,
  PRIMARY KEY (`id_venda`),
  KEY `FK_venda_pessoas` (`id_cliente`),
  KEY `FK_venda_pessoas_2` (`id_vendedor`),
  CONSTRAINT `FK_venda_pessoas` FOREIGN KEY (`id_cliente`) REFERENCES `pessoas` (`id_pessoa`),
  CONSTRAINT `FK_venda_pessoas_2` FOREIGN KEY (`id_vendedor`) REFERENCES `pessoas` (`id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.venda: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
