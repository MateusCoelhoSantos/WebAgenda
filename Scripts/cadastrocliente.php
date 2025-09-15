<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="background-color: #f0f2f5;">

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="text-center mb-0">Cadastro de Novo Cliente</h3>
                </div>
                <div class="card-body p-4">
                    <form action="agenda.php?menuop=inserecliente" method="post">
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="clinome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="clinome" name="clinome" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="clicpfcnpj" class="form-label">CPF/CNPJ</label>
                                <input type="text" class="form-control" id="clicpfcnpj" name="clicpfcnpj" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="clirgie" class="form-label">RG/IE</label>
                                <input type="text" class="form-control" id="clirgie" name="clirgie">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cliemail" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="cliemail" name="cliemail" placeholder="exemplo@email.com">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="clitel" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="clitel" name="clitel" placeholder="(99) 9 9999-9999">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="clinasc" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control" id="clinasc" name="clinasc">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">Tipo Pessoa</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="pessoafisica" name="clitipo" value="0" checked required>
                                    <label class="form-check-label" for="pessoafisica">Física</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="pessoajuridica" name="clitipo" value="1" required>
                                    <label class="form-check-label" for="pessoajuridica">Jurídica</label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label d-block">Gênero</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="masculino" name="cligenero" value="M" required>
                                    <label class="form-check-label" for="masculino">Masculino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="feminino" name="cligenero" value="F" required>
                                    <label class="form-check-label" for="feminino">Feminino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="naoidentificado" name="cligenero" value="N" checked required>
                                    <label class="form-check-label" for="naoidentificado">Não Informado</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer text-end bg-white px-0 pt-3">
                            <a href="agenda.php?menuop=clientes" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success" name="incluir">
                                <i class="bi bi-check-circle"></i> Cadastrar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>