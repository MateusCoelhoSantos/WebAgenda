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
DROP TABLE IF EXISTS `contato`;
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
DROP TABLE IF EXISTS `endereco`;
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
DROP TABLE IF EXISTS `estoque`;
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
DROP TABLE IF EXISTS `grupo`;
CREATE TABLE IF NOT EXISTS `grupo` (
  `id_grupo` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_grupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.grupo: ~0 rows (aproximadamente)
DELETE FROM `grupo`;

-- Copiando estrutura para tabela webagenda.itensvenda
DROP TABLE IF EXISTS `itensvenda`;
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
DROP TABLE IF EXISTS `marca`;
CREATE TABLE IF NOT EXISTS `marca` (
  `id_marca` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_marca`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.marca: ~0 rows (aproximadamente)
DELETE FROM `marca`;

-- Copiando estrutura para tabela webagenda.pessoas
DROP TABLE IF EXISTS `pessoas`;
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
  `genero` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'M-Masculino, F-Feminino, N-Não Identificado',
  `excluido` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_pessoa`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.pessoas: ~64 rows (aproximadamente)
DELETE FROM `pessoas`;
INSERT INTO `pessoas` (`id_pessoa`, `nome`, `cpfcnpj`, `rgie`, `nasc`, `f_j`, `tipopessoa`, `email`, `telefone`, `genero`, `excluido`) VALUES
	(1, 'Mateus Coelho', '02878024265', '0001', '2003-09-13', 0, 1, 'mateus@hotmail.com', '69992134628', 'F', 0),
	(2, 'Mateus Santos', '02878024265', '0001', '2007-09-13', 0, 1, 'Mateus@gmail.com', '69992235346', 'M', 1),
	(4, 'Mateus Coelho', '02878024265', '0001', '2003-09-13', 0, 1, 'mateus@hotmail.com', '69992134628', 'M', 0),
	(5, 'teste2', '123321', '321123', '2024-06-18', 0, 1, 'teste2@gmail.com', '123321', 'N', 1),
	(7, 'amanda amorim', '1234', '1234', '2024-05-31', 1, 1, 'teste', '123321', 'M', 1),
	(8, 'roberto renato', '123', '123', '2024-05-26', 0, 1, 'robren@hotmail.com', '431123', 'F', 1),
	(9, 'jose olivas', '567765', '43232', '2024-03-05', 0, 1, 'teste', 'teste', 'N', 1),
	(10, 'paulinho pereira', '1233254', '123657', '2023-09-06', 0, 1, 'pereira@paulinho.com', 'treqwe', 'M', 0),
	(11, 'atestado', '00.099.876/5431-23', 'eutanasia', '2024-02-16', 1, 1, 'jovem@outlook.com', '', 'N', 0),
	(12, 'abraco', '00.099.876/5431-23', '123345', '2024-01-24', 1, 1, 'jovem@gmail.com', '(69) 9 9223-5646', 'F', 0),
	(13, 'amanda amorim', '1234', '1234', '2004-02-13', 1, 1, 'atualizado@atual.com', '431123', 'F', 1),
	(14, 'jose olivas', '567765', '123657', '2024-05-26', 1, 1, 'jovem', '431123', 'M', 0),
	(15, 'amanda amorim', '1234', '43232', '2024-04-03', 1, 1, 'atualizado@atual.com', '1231231213', 'F', 0),
	(16, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 1),
	(17, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 1),
	(18, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste@hotmail.com', 'teste', 'N', 0),
	(19, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 1),
	(20, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(21, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(24, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(25, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(26, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(27, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(28, 'teste', 'teste', 'teste', '2025-07-08', 0, 1, 'teste', 'teste', 'N', 0),
	(29, 'teste', 'teste', 'teste', '2025-07-02', 0, 1, 'teste', 'teste', 'M', 0),
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
	(66, 'marcos antonio', '01236547895', '01236654', '2025-07-01', 0, 1, 'teste@hotmail.com', '123123', 'M', 0),
	(67, 'teste', 'trdyr', '123123', '2003-09-13', 0, 1, 'tesdte', '123321', 'M', 0),
	(68, 'Amarindo ferreira', '01234567898', '0123546', '1996-06-13', 0, 1, 'amarindo@gmail.com', '69992134628', 'F', 0),
	(69, 'Aparecida Barbosa', '12365478965', '12365', '2001-06-07', 0, 1, 'teste333@gmail.com', '69992134628', 'N', 0);

-- Copiando estrutura para tabela webagenda.quartos
DROP TABLE IF EXISTS `quartos`;
CREATE TABLE IF NOT EXISTS `quartos` (
  `id_quarto` int NOT NULL AUTO_INCREMENT,
  `num_quarto` int DEFAULT NULL,
  `descricao` varchar(250) DEFAULT NULL,
  `status` int DEFAULT NULL,
  `excluido` int DEFAULT NULL,
  `imagem` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_quarto`),
  UNIQUE KEY `num_quarto` (`num_quarto`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.quartos: ~12 rows (aproximadamente)
DELETE FROM `quartos`;
INSERT INTO `quartos` (`id_quarto`, `num_quarto`, `descricao`, `status`, `excluido`, `imagem`) VALUES
	(1, 1, 'Suíte de Luxo', 0, 1, 'PossivelLogo.jpeg'),
	(2, 2, 'suite', 0, 0, ''),
	(3, 3, '1 cama solteiro', 0, 0, ''),
	(5, 33333, 'teste12312', 0, 1, ''),
	(6, 123, 'tste', 0, 1, ''),
	(7, 4568, 'suite de premium plus', 0, 0, ''),
	(8, 4123, 'testeste', 0, 1, NULL),
	(9, 9663, 'ertwsef', 0, 1, NULL),
	(10, 1452, 'o melhor quarto da casa', 0, 0, NULL),
	(11, 44, 'sdfsd', 0, 1, NULL),
	(12, 7676, 'ertert', 0, 0, NULL),
	(13, 6262, 'um quarto qualquer', 0, 0, NULL);

-- Copiando estrutura para tabela webagenda.reservas
DROP TABLE IF EXISTS `reservas`;
CREATE TABLE IF NOT EXISTS `reservas` (
  `id_reserva` int NOT NULL AUTO_INCREMENT,
  `id_pessoa` int NOT NULL DEFAULT '0',
  `tiporeserva` smallint DEFAULT '0',
  `obs` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `horarioini` timestamp NULL DEFAULT NULL,
  `horariofin` timestamp NULL DEFAULT NULL,
  `quant_pessoas` double DEFAULT '0',
  `valor` double DEFAULT '0',
  `finalizado` smallint DEFAULT '0',
  `excluido` int NOT NULL DEFAULT '0',
  `data_reserva` date DEFAULT NULL,
  `id_quarto` int DEFAULT NULL,
  PRIMARY KEY (`id_reserva`),
  KEY `FK__pessoas` (`id_pessoa`),
  KEY `FK_quarto` (`id_quarto`) USING BTREE,
  CONSTRAINT `FK__pessoas` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoas` (`id_pessoa`),
  CONSTRAINT `FK_quarto` FOREIGN KEY (`id_quarto`) REFERENCES `quartos` (`id_quarto`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.reservas: ~5 rows (aproximadamente)
DELETE FROM `reservas`;
INSERT INTO `reservas` (`id_reserva`, `id_pessoa`, `tiporeserva`, `obs`, `horarioini`, `horariofin`, `quant_pessoas`, `valor`, `finalizado`, `excluido`, `data_reserva`, `id_quarto`) VALUES
	(5, 1, 0, '0', '2025-09-15 03:35:00', '2025-10-15 03:35:00', 0, 150, 0, 0, '2025-08-20', 1),
	(8, 1, 0, '0', '2025-09-15 03:18:00', '2025-09-15 03:18:00', 2, 500, 0, 1, '2025-09-14', 1),
	(9, 1, 0, 'tudo normal por aqui. só que não', '2025-09-15 02:46:00', '2025-09-15 02:46:00', 1, 500, 0, 0, '2025-09-14', 2),
	(10, 15, 0, 'vai chegar depois do horario de check-in', '2025-09-15 05:02:00', '2025-10-15 05:02:00', 1, 500, 0, 1, '2025-09-15', 7),
	(11, 4, 0, '', '2025-09-15 05:07:00', '2025-09-20 05:07:00', 2, 200, 0, 0, '2025-09-15', 5);

-- Copiando estrutura para tabela webagenda.usuarios
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `senha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `token_expira_em` datetime DEFAULT NULL,
  `excluido` int DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela webagenda.usuarios: ~2 rows (aproximadamente)
DELETE FROM `usuarios`;
INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `senha`, `telefone`, `reset_token`, `token_expira_em`, `excluido`, `cpf`, `foto_perfil`) VALUES
	(6, 'Mateus Coelho', 'mateuscoelhosoporo@gmail.com', '$2y$10$ySPcrKBLU0.NtOnF19B97eeVuPr41BSC0J.ztu0.oupzVkr719kRO', '69992134628', NULL, NULL, 0, '02878024265', '68cb2954abf9f_1758144852.jpg'),
	(7, 'Mateus Coelho Santos', 'mateuscoelhosoporo@hotmail.com', '$2y$10$12RcMTKuiA6VBiqzfqy1q.XnNeCqHYEWrom/5vTXVr5DSvNes4UVe', '69992134628', NULL, NULL, 0, '02878024265', NULL);

-- Copiando estrutura para tabela webagenda.venda
DROP TABLE IF EXISTS `venda`;
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
DELETE FROM `venda`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
