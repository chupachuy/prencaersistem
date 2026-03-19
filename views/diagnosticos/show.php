<?php 
$title = "Detalle del Diagnóstico";
require_once __DIR__ . '/../layouts/header.php'; 
require_once __DIR__ . '/../layouts/sidebar.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="javascript:history.back()" class="btn btn-light border btn-sm"><i class="fa-solid fa-arrow-left"></i> Volver</a>
        <div>
            <h2 class="page-title mb-0">Registro Médico #<?php echo htmlspecialchars($diagnostico['id'] ?? 'N/A'); ?></h2>
        </div>
    </div>
    <button class="btn btn-outline-secondary btn-sm" onclick="window.print()"><i class="fa-solid fa-print"></i> Imprimir</button>
</div>

<?php if (empty($diagnostico)): ?>
    <div class="alert alert-warning">No se encontró el diagnóstico.</div>
<?php else: ?>
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary fw-bold"><i class="fa-solid fa-file-medical me-2"></i> Información del Diagnóstico</h5>
                    <span class="badge bg-light text-dark border"><i class="fa-regular fa-calendar me-1"></i> <?php echo date('d/m/Y H:i', strtotime($diagnostico['fecha'] ?? 'now')); ?></span>
                </div>
                <div class="card-body p-4">
                    
                    <div class="row mb-4">
                        <div class="col-md-6 border-end">
                            <p class="text-muted small mb-1 fw-bold text-uppercase">Paciente</p>
                            <p class="fs-5 text-dark fw-medium mb-0"><i class="fa-solid fa-user-injured text-secondary me-2"></i> <?php echo htmlspecialchars($diagnostico['paciente'] ?? 'No especificado'); ?></p>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <p class="text-muted small mb-1 fw-bold text-uppercase">Médico Tratante</p>
                            <p class="fs-5 text-dark fw-medium mb-0"><i class="fa-solid fa-user-doctor text-primary me-2"></i> <?php echo htmlspecialchars($diagnostico['medico'] ?? 'N/A'); ?></p>
                        </div>
                    </div>

                    <hr class="bg-light opacity-10 mt-4 mb-4">

                    <div class="mb-4">
                        <p class="text-muted small mb-2 fw-bold text-uppercase">Descripción Diagnóstica</p>
                        <div class="bg-light p-3 rounded-3 border">
                            <p class="mb-0 text-dark"><?php echo nl2br(htmlspecialchars($diagnostico['descripcion'] ?? 'Sin descripción disponible.')); ?></p>
                        </div>
                    </div>

                    <div>
                        <p class="text-muted small mb-2 fw-bold text-uppercase">Tratamiento o Receta</p>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 border border-primary border-opacity-25">
                            <p class="mb-0 text-dark"><?php echo nl2br(htmlspecialchars($diagnostico['tratamiento'] ?? 'No se prescribió tratamiento o receta.')); ?></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>






