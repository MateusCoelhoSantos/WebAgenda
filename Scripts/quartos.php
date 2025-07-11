<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quartos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<header>
    <center><h3>Quartos</h3></center>
</header>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="incluir_quarto.php" class="btn btn-success">Incluir Quarto</a>
    </div>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Número</th>
                <th>Tipo</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $sql = "select * from quartos"; 

            $RS = mysqli_query($conexao,$sql) or die("Erro ao Executar a Consulta!" . mysqli_error($conexao));

            if (mysqli_num_rows($RS) > 0) {
                foreach ($RS as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['id_quarto'] . "</td>";
                    echo "<td>" . $row['num_quarto'] . "</td>";
                    echo "<td>" . $row['descricao'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>
                        <a href='alterar_quarto.php?id_quarto=" . $row['id_quarto'] . "' class='btn btn-primary btn-sm me-1'>Alterar</a>
                        <a href='excluir_quarto.php?id_quarto=" . $row['id_quarto'] . "' class='btn btn-danger btn-sm' onclick='return confirm(Deseja realmente excluir?)'>Excluir</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>Nenhum quarto cadastrado.</td></tr>";
            }
            
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>