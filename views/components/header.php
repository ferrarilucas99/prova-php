<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/users' ? 'active' : '' ?>" aria-current="page" href="/users">Usuários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/colors' ? 'active' : '' ?>" href="/colors">Cores</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>