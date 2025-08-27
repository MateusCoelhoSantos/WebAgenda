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

-- Copiando dados para a tabela webagenda.contato: ~0 rows (aproximadamente)
DELETE FROM `contato`;

-- Copiando dados para a tabela webagenda.endereco: ~0 rows (aproximadamente)
DELETE FROM `endereco`;

-- Copiando dados para a tabela webagenda.estoque: ~0 rows (aproximadamente)
DELETE FROM `estoque`;

-- Copiando dados para a tabela webagenda.grupo: ~0 rows (aproximadamente)
DELETE FROM `grupo`;

-- Copiando dados para a tabela webagenda.itensvenda: ~0 rows (aproximadamente)
DELETE FROM `itensvenda`;

-- Copiando dados para a tabela webagenda.marca: ~0 rows (aproximadamente)
DELETE FROM `marca`;

-- Copiando dados para a tabela webagenda.pessoas: ~61 rows (aproximadamente)
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
	(12, 'abraco', '099876543123', '123345', '2024-01-24', 1, 1, 'jovem', '123321', 'F', 0),
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
	(66, 'marcos antonio', '012365478952', '01236654', '2025-07-01', 0, 1, 'teste', 'tesste123123', 'M', 0);

-- Copiando dados para a tabela webagenda.quartos: ~3 rows (aproximadamente)
DELETE FROM `quartos`;
INSERT INTO `quartos` (`id_quarto`, `num_quarto`, `descricao`, `status`, `excluido`, `imagem`) VALUES
	(1, 1, 'Suíte de Luxo', 0, 0, 'PossivelLogo.jpeg'),
	(2, 2, 'suite', 0, 0, ''),
	(3, 3, '1 cama solteiro', 0, 0, '');

-- Copiando dados para a tabela webagenda.reservas: ~0 rows (aproximadamente)
DELETE FROM `reservas`;
INSERT INTO `reservas` (`id_reserva`, `id_pessoa`, `tiporeserva`, `id_quarto`, `horarioini`, `horariofin`, `quant`, `valor`, `finalizado`) VALUES
	(5, 1, 0, 1, '08:00:00', '18:00:00', 0, 150, 0);

-- Copiando dados para a tabela webagenda.usuarios: ~4 rows (aproximadamente)
DELETE FROM `usuarios`;
INSERT INTO `usuarios` (`id_usuario`, `id_pessoa`, `nome`, `email`, `senha`, `telefone`) VALUES
	(1, NULL, '', 'mateuscoelhosoporo@gmail.com', 'mateus13', '69992134628'),
	(2, NULL, '', 'mateuscoelhosoporo@gmail.com', 'mateus13', '69992134628'),
	(3, NULL, '', 'mateuscoelhosoporo@gmail.com', 'mateus13', '69992134628'),
	(4, NULL, 'Mateus Coelho', 'mateuscoelhosoporo@gmail.com', 'mat', '69992134628');

-- Copiando dados para a tabela webagenda.venda: ~0 rows (aproximadamente)
DELETE FROM `venda`;
INSERT INTO `venda` (`id_venda`, `id_cliente`, `tipovenda`, `total`) VALUES
	(1, 1, 0, 150);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
