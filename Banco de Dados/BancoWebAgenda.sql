-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.4.3 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.8.0.6908
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
DELETE FROM `contato`;

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
DELETE FROM `endereco`;

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
DELETE FROM `estoque`;

-- Copiando estrutura para tabela webagenda.grupo
CREATE TABLE IF NOT EXISTS `grupo` (
  `id_grupo` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.grupo: ~0 rows (aproximadamente)
DELETE FROM `grupo`;

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
DELETE FROM `itensvenda`;

-- Copiando estrutura para tabela webagenda.marca
CREATE TABLE IF NOT EXISTS `marca` (
  `id_marca` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_marca`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.marca: ~0 rows (aproximadamente)
DELETE FROM `marca`;

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
  `excluido` int DEFAULT NULL,
  PRIMARY KEY (`id_pessoa`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.pessoas: ~60 rows (aproximadamente)
DELETE FROM `pessoas`;
INSERT INTO `pessoas` (`id_pessoa`, `nome`, `cpfcnpj`, `rgie`, `nasc`, `f_j`, `tipopessoa`, `email`, `telefone`, `orientacaosex`, `excluido`) VALUES
	(1, 'Mateus Coelho', '02878024265', '0001', '2003-09-13', 0, 1, 'mateus@hotmail.com', '69992134628', 'F', 0),
	(2, 'Mateus Santos', '02878024265', '0001', '2007-09-13', 0, 1, 'Mateus@gmail.com', '69992235346', 'M', 1),
	(4, 'Mateus Coelho', '02878024265', '0001', '2003-09-13', 0, 1, 'mateus@hotmail.com', '69992134628', 'M', 0),
	(5, 'teste2', '123321', '321123', '2024-06-18', 0, 1, 'teste2@gmail.com', '123321', 'N', 0),
	(7, 'amanda amorim', '1234', '1234', '2024-05-31', 1, 1, 'teste', '123321', 'F', 0),
	(8, 'roberto renato', '123', '123', '2024-05-26', 0, 1, 'robren@hotmail.com', '431123', 'N', 0),
	(9, 'jose olivas', '567765', '43232', '2024-03-05', 0, 1, 'teste', 'teste', 'N', 1),
	(10, 'paulinho pereira', '1233254', '123657', '2023-09-06', 0, 1, 'pereira@paulinho.com', 'treqwe', 'M', 0),
	(11, 'atestado', 'amianto', 'eutanasia', '2024-02-16', 1, 1, 'jovem', 'magaiver', 'N', 0),
	(12, 'abraco', '09987654312312', '123345', '2024-01-24', 1, 1, 'jovem', '123321', 'F', 0),
	(13, 'amanda amorim', '1234', '1234', '2004-02-13', 1, 1, 'atualizado@atual.com', '431123', 'F', 0),
	(14, 'jose olivas', '567765', '123657', '2024-05-26', 1, 1, 'jovem', '431123', 'M', 0),
	(15, 'amanda amorim', '1234', '43232', '2024-04-03', 1, 1, 'atualizado@atual.com', '1231231213', 'N', 0),
	(16, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(17, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(18, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(19, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(20, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(21, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(24, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(25, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 1),
	(26, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 1),
	(27, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 1),
	(28, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(29, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 1),
	(31, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(32, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(33, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(34, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(35, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(36, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(37, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(38, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(39, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(40, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(41, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(42, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(43, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(44, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(45, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(46, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(47, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(48, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(49, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(50, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(51, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(52, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(53, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(54, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(55, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(56, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(57, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(58, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(59, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(60, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(61, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(62, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(63, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(64, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
	(65, 'teste3123', 'teste123', 'teste12312', '2025-07-07', 1, 1, 'teste12312', 'tesste123123', 'N', 0),
	(66, 'marcos antonio', '012365478952', '01236654', '2025-07-01', 0, 1, 'teste', 'tesste123123', 'M', 0);

-- Copiando estrutura para tabela webagenda.quartos
CREATE TABLE IF NOT EXISTS `quartos` (
  `id_quarto` int NOT NULL AUTO_INCREMENT,
  `num_quarto` int DEFAULT NULL,
  `descricao` varchar(250) DEFAULT '',
  `status` int DEFAULT NULL,
  `excluido` int DEFAULT NULL,
  `imagem` varchar(500) NOT NULL,
  PRIMARY KEY (`id_quarto`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.quartos: ~22 rows (aproximadamente)
DELETE FROM `quartos`;
INSERT INTO `quartos` (`id_quarto`, `num_quarto`, `descricao`, `status`, `excluido`, `imagem`) VALUES
	(1, 1, 'Suíte de Luxo', 0, 0, 'PossivelLogo.jpeg'),
	(2, 2, 'suite', 0, 0, ''),
	(3, 3, '1 cama solteiro', 0, 0, ''),
	(4, 1, 'Suíte de Luxo', 0, 1, ''),
	(5, 1, 'Suíte de Luxo', 0, 1, ''),
	(6, 1, 'Suíte de Luxo', 0, 1, ''),
	(7, 63, 'Quarto de casal plus', 0, 0, ''),
	(8, 85, 'teste', 0, 0, ''),
	(9, 789, 'testes', 0, 0, ''),
	(10, 123, 'outro teste', 0, 0, ''),
	(11, 12312, 'testes', 0, 0, ''),
	(12, 135, 'testeee', 0, 0, ''),
	(13, 1231231, 'testes', 0, 0, ''),
	(14, 79878, 'teste', 0, 0, ''),
	(15, 5675, 'teste', 0, 0, ''),
	(16, 76575, 'testes', 0, 0, ''),
	(17, 3545, 'teste', 0, 0, ''),
	(18, 56457, 'testes', 0, 0, ''),
	(19, 899, 'testes', 0, 0, ''),
	(20, 8765865, 'teste', 0, 0, ''),
	(21, 987689, 'testes', 0, 0, ''),
	(22, 7546, 'teste', 0, 1, '');

-- Copiando estrutura para tabela webagenda.reservas
CREATE TABLE IF NOT EXISTS `reservas` (
  `id_reserva` int NOT NULL AUTO_INCREMENT,
  `id_pessoa` int NOT NULL DEFAULT '0',
  `tiporeserva` smallint DEFAULT '0',
  `id_quarto` int NOT NULL DEFAULT '0',
  `horarioini` datetime DEFAULT NULL,
  `horariofin` datetime DEFAULT NULL,
  `valor` double DEFAULT '0',
  `quant_pessoas` double DEFAULT '0',
  `finalizado` smallint DEFAULT '0',
  `excluido` int DEFAULT NULL,
  `data_reserva` date DEFAULT NULL,
  PRIMARY KEY (`id_reserva`),
  KEY `FK__pessoas` (`id_pessoa`),
  KEY `FK__venda` (`id_quarto`) USING BTREE,
  CONSTRAINT `FK__pessoas` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoas` (`id_pessoa`),
  CONSTRAINT `FK__venda` FOREIGN KEY (`id_quarto`) REFERENCES `venda` (`id_venda`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.reservas: ~2 rows (aproximadamente)
DELETE FROM `reservas`;
INSERT INTO `reservas` (`id_reserva`, `id_pessoa`, `tiporeserva`, `id_quarto`, `horarioini`, `horariofin`, `valor`, `quant_pessoas`, `finalizado`, `excluido`, `data_reserva`) VALUES
	(5, 1, 0, 1, '2025-08-05 15:37:49', '2025-08-25 14:54:36', 150, 0, 0, 0, '2025-08-05'),
	(6, 12, 0, 1, NULL, NULL, 200, 0, 0, 0, '2025-07-05');

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
DELETE FROM `usuarios`;
INSERT INTO `usuarios` (`id_usuario`, `id_pessoa`, `nome`, `email`, `senha`, `telefone`) VALUES
	(1, NULL, '', 'mateuscoelhosoporo@gmail.com', 'mateus13', '69992134628'),
	(2, NULL, '', 'mateuscoelhosoporo@gmail.com', 'mateus13', '69992134628'),
	(3, NULL, '', 'mateuscoelhosoporo@gmail.com', 'mateus13', '69992134628'),
	(4, NULL, 'Mateus Coelho', 'mateuscoelhosoporo@gmail.com', 'mat', '69992134628');

-- Copiando estrutura para tabela webagenda.venda
CREATE TABLE IF NOT EXISTS `venda` (
  `id_venda` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `tipovenda` smallint DEFAULT NULL,
  `total` double DEFAULT NULL,
  PRIMARY KEY (`id_venda`),
  KEY `FK_venda_pessoas` (`id_cliente`),
  CONSTRAINT `FK_venda_pessoas` FOREIGN KEY (`id_cliente`) REFERENCES `pessoas` (`id_pessoa`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.venda: ~0 rows (aproximadamente)
DELETE FROM `venda`;
INSERT INTO `venda` (`id_venda`, `id_cliente`, `tipovenda`, `total`) VALUES
	(1, 1, 0, 150);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
