<?php
// sidebar.php
$roleId = Session::get('user_role_id');
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Create base path prefix
$basePath = Url::base();

// Adjust the matching strpos to remove basePath if needed
$matchPath = str_replace($basePath, '', $currentPath);
if ($matchPath === '') $matchPath = '/';
?>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">

        <img width="120" src="<?php echo Url::to('/'); ?>/logos/logocolor.svg" alt="logo">
        <!--<i class="fa-solid fa-heart-pulse"></i>
        <h5>PreNacer</h5>-->
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="<?= Url::to('/dashboard') ?>" class="<?php echo $matchPath == '/dashboard' ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>
        </li>

        <?php if ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR || $roleId == Auth::ROLE_JEFE): ?>
        <li>
            <a href="<?= Url::to('/usuarios') ?>" class="<?php echo strpos($matchPath, '/usuarios') === 0 ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i> Usuarios
            </a>
        </li>
        <li>
            <a href="<?= Url::to('/asignaciones') ?>" class="<?php echo strpos($matchPath, '/asignaciones') === 0 ? 'active' : ''; ?>">
                <i class="fa-solid fa-list-check"></i> Asignaciones
            </a>
        </li>
        <li>
            <a href="<?= Url::to('/informes_exploracion') ?>" class="<?php echo strpos($matchPath, '/informes_exploracion') === 0 ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-medical-alt"></i> Informes Exploracion
            </a>
        </li>
        <?php endif; ?>

        <?php if ($roleId == Auth::ROLE_MEDICO || $roleId == Auth::ROLE_JEFE || $roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR): ?>
        <li>
            <a href="<?= Url::to('/diagnosticos') ?>" class="<?php echo strpos($matchPath, '/diagnosticos') === 0 && strpos($matchPath, '/diagnosticos/todos') === false ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-medical"></i> Mis Diagnósticos
            </a>
        </li>
        <?php endif; ?>
        
        <?php if ($roleId != Auth::ROLE_MEDICO): ?>
        <li>
            <a href="<?= Url::to('/diagnosticos/todos') ?>" class="<?php echo $matchPath == '/diagnosticos/todos' ? 'active' : ''; ?>">
                <i class="fa-solid fa-notes-medical"></i> Todos los Diagnósticos
            </a>
        </li>
        <?php endif; ?>

        <?php if ($roleId == Auth::ROLE_MEDICO): ?>
        <li>
            <a href="<?= Url::to('/pacientes') ?>" class="<?php echo strpos($matchPath, '/pacientes') === 0 ? 'active' : ''; ?>">
                <i class="fa-solid fa-bed-pulse"></i> Mis Pacientes
            </a>
        </li>
        <?php endif; ?>

        <?php if ($roleId == Auth::ROLE_SUPERADMIN || $roleId == Auth::ROLE_ADMINISTRADOR || $roleId == Auth::ROLE_JEFE): ?>
        <li>
            <a href="<?= Url::to('/pacientes') ?>" class="<?php echo strpos($matchPath, '/pacientes') === 0 && strpos($matchPath, '/asignaciones') === false ? 'active' : ''; ?>">
                <i class="fa-solid fa-users-viewfinder"></i> Pacientes
            </a>
        </li>
        <li>
            <a href="<?= Url::to('/asignaciones') ?>" class="<?php echo strpos($matchPath, '/asignaciones') === 0 ? 'active' : ''; ?>">
                <i class="fa-solid fa-list-check"></i> Asignaciones
            </a>
        </li>
        <?php endif; ?>
    </ul>
    
    <div class="mt-auto p-3 border-top" style="border-color: rgba(0,0,0,0.04) !important;">
        <a href="<?= Url::to('/logout') ?>" class="logout-btn w-100">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión
        </a>
    </div>
</aside>

<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Top Navbar -->
    <header class="top-navbar">
        <div class="d-flex align-items-center">
            <button class="btn btn-light d-lg-none me-3" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h5 class="m-0" style="color: var(--apple-gray); font-weight: 500;"><?php echo htmlspecialchars($title ?? 'Panel'); ?></h5>
        </div>
        
        <div class="navbar-user dropdown">
            <div class="user-info d-none d-md-block me-3">
                <div class="user-name"><?php echo htmlspecialchars($user['nombre'] ?? 'Usuario'); ?></div>
                <div class="user-role"><?php echo htmlspecialchars($user['rol_nombre'] ?? ''); ?></div>
            </div>
            <div class="user-avatar dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo strtoupper(substr($user['nombre'] ?? 'U', 0, 1)); ?>
            </div>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px; padding: 8px;">
                <li><a class="dropdown-item" href="<?= Url::to('/perfil') ?>" style="border-radius: 8px; padding: 10px 14px;"><i class="fa-regular fa-user me-2"></i> Mi Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="<?= Url::to('/logout') ?>" style="border-radius: 8px; padding: 10px 14px;"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
    </header>

    <!-- Content Area that views will fill -->
    <main class="content-area">
        <?php if ($flashError = Session::getFlash('error')): ?>
            <div class="alert" style="background: #ffebe6; color: #bf2b2b; border-radius: 12px;" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo htmlspecialchars($flashError); ?>
            </div>
        <?php endif; ?>

        <?php if ($flashSuccess = Session::getFlash('success')): ?>
            <div class="alert" style="background: #d1e7dd; color: #367d84; border-radius: 12px;" role="alert">
                <i class="fa-solid fa-check-circle me-2"></i> <?php echo htmlspecialchars($flashSuccess); ?>
            </div>
        <?php endif; ?>
