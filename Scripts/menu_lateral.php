<div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateral" aria-labelledby="menuLateralLabel">
    
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="menuLateralLabel">WebAgenda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="agenda.php?menuop=agendamento">
                    <i class="bi bi-calendar-check-fill"></i> Agendamentos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="agenda.php?menuop=clientes">
                    <i class="bi bi-person-plus-fill"></i> Clientes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="agenda.php?menuop=quartos">
                    <i class="bi bi-door-open-fill"></i> Quartos
                </a>
            </li>
            <li class="nav-item dropdown mt-3 border-top pt-3">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Usuário
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Meu Perfil</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="index.php">Sair</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>