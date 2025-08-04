<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Agendamentos</title>

    <style>
        .container{
            display: flex;
            width: 100vw;
            height: 100px;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<header>
    <center><h3>Agendamentos</h3></center>
</header>
<body>
    <div class="container">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Nome</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $quantidade = 20;

            $pagina = (isset($_GET['pagina']))?(int)$_GET['pagina']:1;

            $inicio = ($quantidade * $pagina) - $quantidade;

            $pesquisa = (isset($_POST["pesquisa"]))?$_POST["pesquisa"]:"";
            
            $sql = "select r.id_reserva, r.valor as valor, r.finalizado, p.id_pessoa, p.nome as nome, p.email as email, p.telefone as telefone from reservas r inner join pessoas p on r.id_pessoa = p.id_pessoa";
            $dados = mysqli_query($conexao,$sql) or die("Erro ao Executar a Consulta!" . mysqli_error($conexao));
            foreach ($dados as $reserva): ?>
                <tr data-bs-toggle="collapse" data-bs-target="#detalhes<?= $reserva['id_reserva'] ?>" class="cursor-pointer">
                    <td><?= $reserva['nome'] ?></td>
                    <td><?= $reserva['email'] ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="p-0 border-0">
                        <div id="detalhes<?= $reserva['id_reserva'] ?>" class="collapse">
                            <div class="p-3 bg-light border-top">
                                <?= $reserva['telefone'] ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>