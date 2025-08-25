<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Agendamentos</title>

    <style>
        .row{
            display: flex;
            margin-top: 25px;
            margin-bottom: 25px;
        }
        .a{
            margin-trim: all;
        }
        .form{
            display: flex;
        }
        .form-control{
            margin-right: 10px;
            width: 250px;
        }
    </style>
</head>
<header>
    <center><h3>Agendamentos</h3></center>
</header>
<body>
    <div class="container">
       <div class="row align-items-center mb-4">
            <!-- Campo de pesquisa -->
            <div class="col-md-5">
                <form class="d-flex" action="agenda.php?menuop=agendamento" method="post">
                    <input class="form-control me-2" type="text" name="pesquisa" placeholder="Pesquisar"> 
                    <input class="btn btn-success" type="submit" value="Pesquisar">

                    <?php $hoje = date('Y-m-d');?>
                    <div class="col-md-4 d-flex align-items-center">
                        <input class="form-control me-2" type="date" name="dataini" value="<?= $hoje ?>">
                        <span class="me-2">até</span>
                        <input class="form-control me-2" type="date" name="datafin" value="<?= $hoje ?>">
                    </div>
                </form>
            </div>
            
            <!-- Botão novo agendamento -->
            <div class="col-md-7 text-end">
                <a href="agenda.php?menuop=cadastroagendamento" class="btn btn-success">Incluir Agendamento</a>
            </div>
    </div>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Cliente</th>
                <th>Quarto</th>
                <th>Horario</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php 

            ini_set('display_errors', 1);
            error_reporting(E_ALL);

            function formatarTelefone($telefone) {
                $telefone = preg_replace('/\D/', '', $telefone);
                if (strlen($telefone) === 11) {
                    return preg_replace('/^(\d{2})(\d{1})(\d{4})(\d{4})$/', '($1) $2 $3-$4', $telefone);
                } else {
                    return $telefone; // Se não for 11 dígitos, retorna como está
                }
            }

            function formatarCpfCnpj($numero) {
                // Remove tudo que não for número
                $numero = preg_replace('/\D/', '', $numero);

                if (strlen($numero) === 11) {
                    // Formatar CPF: 000.000.000-00
                    return preg_replace('/^(\d{3})(\d{3})(\d{3})(\d{2})$/', '$1.$2.$3-$4', $numero);
                } elseif (strlen($numero) === 14) {
                    // Formatar CNPJ: 00.000.000/0000-00
                    return preg_replace('/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/', '$1.$2.$3/$4-$5', $numero);
                } else {
                    // Número inválido
                    return $numero;
                }
            }

            $quantidade = 20;

            $pagina = (isset($_GET['pagina']))?(int)$_GET['pagina']:1;

            $inicio = ($quantidade * $pagina) - $quantidade;

            $pesquisa = $_POST["pesquisa"] ?? ""; 
            $dataini = $_POST["dataini"] ?? $hoje;
            $datafin = $_POST["datafin"] ?? $hoje;
            
            $sql = "SELECT 
                        *
                    FROM 
                        reservas r 
                    INNER JOIN
                        pessoas p 
                    ON 
                        r.id_pessoa = p.id_pessoa 
                    INNER JOIN
                        quartos q 
                    ON 
                        r.id_quarto = q.id_quarto
                    WHERE (r.excluido <> 1) AND finalizado = 0 AND (num_quarto = '{$pesquisa}' OR nome LIKE '%{$pesquisa}%') and (data_reserva between '{$dataini}' and '{$datafin}')   
                    ORDER BY id_reserva, horarioini
                    LIMIT $inicio , $quantidade";

            $dados = mysqli_query($conexao,$sql) or die("Erro ao Executar a Consulta!" . mysqli_error($conexao));
            foreach ($dados as $reserva): 
            ?>
                <tr>
                    <td><?= $reserva['nome'] ?></td>
                    <td><?= $reserva['num_quarto'] .' - '. $reserva['descricao'] ?></td>
                    <td><?= date("d/m/Y H:i", strtotime($reserva['horarioini'])) .' - '. date("d/m/Y H:i", strtotime($reserva['horariofin']))  ?></td>
                    <td>
                        <button class="btn btn-success toggle-collapse" data-bs-toggle="collapse" data-bs-target="#detalhes<?= $reserva['id_reserva'] ?>">
                            Detalhes
                        </button>
                        <a href="agenda.php?menuop=editaragendamento&idreserva=<?= $reserva["id_reserva"] ?>" class="btn btn-primary">Alterar</a>
                        <a href="agenda.php?menuop=excluiragendamento&idreserva=<?= $reserva["id_reserva"] ?>" class="btn btn-danger">Excluir</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="p-0 border-0">
                        <div id="detalhes<?= $reserva['id_reserva'] ?>" class="collapse">
                            <div class="p-3 bg-light border-top">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="/Imagens/<?= $reserva['imagem'] ?>" class="img-fluid" style="max-height: 300px;">
                                    </div>
                                    <div class="col-md-8 d-flex flex-column justify-content-start">
                                        <?php
                                        $dataNascimento = new DateTime($reserva['nasc']);
                                        $hoje = new DateTime();
                                        $idade = $hoje->diff($dataNascimento)->y;
                                        ?>
                                        <p><strong>CPF/CNPJ:</strong> <?= formatarCpfCnpj($reserva['cpfcnpj']) ?></p>
                                        <p><strong>Telefone:</strong> <?= formatarTelefone($reserva['telefone']) ?></p>
                                        <p><strong>Email:</strong> <?= $reserva['email'] ?></p>
                                        <p><strong>Nascimento:</strong> <?= date("d/m/Y", strtotime($reserva['nasc'])) ?></p>
                                        <p><strong>Idade:</strong> <?= $idade ?> Anos</p>
                                    </div>
                                </div>
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