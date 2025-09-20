<div class="offcanvas offcanvas-start text-bg-white" tabindex="-1" id="menuLateral" aria-labelledby="menuLateralLabel">
    
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="menuLateralLabel">
            <i class="bi bi-calendar-check"></i> WebAgenda
        </h5>
        <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item">
                <a class="nav-link active" href="agenda.php?menuop=agendamento">
                    <i class="bi bi-calendar-event me-2"></i> Agendamentos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="agenda.php?menuop=clientes">
                    <i class="bi bi-people-fill me-2"></i> Clientes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="agenda.php?menuop=quartos">
                    <i class="bi bi-key-fill me-2"></i> Quartos
                </a>
            </li>
            <li class="nav-item dropdown mt-auto border-top border-secondary pt-3">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php
                        // Define o caminho da foto
                        $foto_perfil = $_SESSION['user_photo'] ?? null;
                        $caminho_foto = '../Imagens/Usuarios/default-avatar.png'; // Caminho para uma imagem padrão

                        if (!empty($foto_perfil) && file_exists('../Imagens/Usuarios/' . $foto_perfil)) {
                            $caminho_foto = '../Imagens/Usuarios/' . $foto_perfil;
                        }
                    ?>
                    <img src="<?= $caminho_foto ?>" alt="Foto de Perfil" width="32" height="32" class="rounded-circle me-2">
                    <span>Olá, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuário') ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="agenda.php?menuop=perfil">
                            <i class="bi bi-person-gear me-2"></i> Meu Perfil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="index.php">
                            <i class="bi bi-box-arrow-right me-2"></i> Sair
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>