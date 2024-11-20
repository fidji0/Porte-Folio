<?php if (isset($_GET['success'])) : ?>
    <div class="alert alert-success  alert-dismissible fade show" style="position : absolute ; top : 10px; z-index : 100; right : 10px" role="alert">
        <strong><?= isset($success) ? $success : (isset($_GET['success']) && $_GET['success'] != '1' ? base64_decode($_GET['success']) : "Action réalisée avec succes") ?></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif ?>
<?php if (isset($error)) : ?>
    <div class="alert alert-danger  alert-dismissible fade show" style="position : absolute ; top : 10px; z-index : 100; right : 10px" role="alert">
        <strong><?= isset($error) ? $error : (isset($_GET['error']) && $_GET['error'] != '1' ? base64_decode($_GET['error']) : "Une erreur c'est produite") ?></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif ?>
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION["name"] . " " . $_SESSION["lastname"] ?></span>
                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#?logout=1" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Déconnexion
                </a>
            </div>
        </li>
    </ul>
</nav>