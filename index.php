<?php
session_start();

// Enable Error Reporting for Dev
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/PasswordResetController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/DiagnosticoController.php';
require_once __DIR__ . '/controllers/AsignacionController.php';
require_once __DIR__ . '/controllers/PacienteController.php';
require_once __DIR__ . '/controllers/PerfilController.php';
require_once __DIR__ . '/controllers/InformesExploracionController.php';
require_once __DIR__ . '/controllers/ConsultaController.php';
require_once __DIR__ . '/controllers/Reporte1erTrimestreController.php';

$router = new Router();

// Dashboard
$router->get('/dashboard', [DashboardController::class, 'index']);

// Perfil
$router->get('/perfil', [PerfilController::class, 'index']);

// Usuarios / Medicos
$router->get('/usuarios', [UserController::class, 'index']);
$router->get('/usuarios/create', [UserController::class, 'create']);
$router->post('/usuarios/store', [UserController::class, 'store']);
$router->get('/usuarios/edit', [UserController::class, 'edit']);
$router->post('/usuarios/update', [UserController::class, 'update']);

// Diagnosticos
$router->get('/diagnosticos', [DiagnosticoController::class, 'index']);
$router->get('/diagnosticos/todos', [DiagnosticoController::class, 'todos']);
$router->get('/diagnosticos/create', [DiagnosticoController::class, 'create']);
$router->post('/diagnosticos/store', [DiagnosticoController::class, 'store']);
$router->get('/diagnosticos/show', [DiagnosticoController::class, 'show']);
$router->get('/diagnosticos/edit', [DiagnosticoController::class, 'edit']);
$router->post('/diagnosticos/update', [DiagnosticoController::class, 'update']);

// Asignaciones
$router->get('/asignaciones', [AsignacionController::class, 'index']);
$router->get('/asignaciones/mis-pacientes', [AsignacionController::class, 'misPacientes']); // Maybe this can route to Pacientes now, but leaving for backwards compatibility
$router->get('/asignaciones/create', [AsignacionController::class, 'create']);
$router->post('/asignaciones/store', [AsignacionController::class, 'store']);

// Pacientes
$router->get('/pacientes', [PacienteController::class, 'index']);
$router->get('/pacientes/create', [PacienteController::class, 'create']);
$router->post('/pacientes/store', [PacienteController::class, 'store']);

// Informes de Exploración
$router->get('/informes_exploracion', [InformesExploracionController::class, 'index']);
$router->get('/informes_exploracion/create', [InformesExploracionController::class, 'create']);
$router->post('/informes_exploracion/store', [InformesExploracionController::class, 'store']);
$router->get('/informes_exploracion/show', [InformesExploracionController::class, 'show']);
$router->get('/informes_exploracion/edit', [InformesExploracionController::class, 'edit']);
$router->post('/informes_exploracion/update', [InformesExploracionController::class, 'update']);
$router->get('/informes_exploracion/delete', [InformesExploracionController::class, 'delete']);
$router->get('/informes_exploracion/por-paciente', [InformesExploracionController::class, 'porPaciente']);

// Consultas
$router->get('/consultas', [ConsultaController::class, 'index']);
$router->get('/consultas/create', [ConsultaController::class, 'create']);
$router->post('/consultas/store', [ConsultaController::class, 'store']);
$router->get('/consultas/show', [ConsultaController::class, 'show']);

// Reporte 1er Trimestre
$router->get('/reporte_1er_trimestre/create', [Reporte1erTrimestreController::class, 'create']);
$router->post('/reporte_1er_trimestre/store', [Reporte1erTrimestreController::class, 'store']);
$router->get('/reporte_1er_trimestre/show', [Reporte1erTrimestreController::class, 'show']);
$router->get('/reporte_1er_trimestre/edit', [Reporte1erTrimestreController::class, 'edit']);
$router->post('/reporte_1er_trimestre/update', [Reporte1erTrimestreController::class, 'update']);
$router->get('/reporte_1er_trimestre/print', [Reporte1erTrimestreController::class, 'print']);

// Auth Routes
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Password Reset Routes
$router->get('/forgot-password', [PasswordResetController::class, 'showForgotForm']);
$router->post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
$router->get('/reset-password', [PasswordResetController::class, 'showResetForm']);
$router->post('/reset-password', [PasswordResetController::class, 'resetPassword']);

// Main Entry Route (Login)
$router->get('/', [AuthController::class, 'showLogin']);

// Debug marker
$router->resolve();
