<?php
$title = "Referencias";
$meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$fecha_hoy = date('j') . ' de ' . $meses[date('n')] . ' del ' . date('Y');
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

$currentUserId = Session::get('user_id');
$currentRoleId = Session::get('user_role_id');
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h1 class="page-title mb-0">Referencias</h1>
    </div>
    <div class="page-header-actions">
        <span class="text-muted me-3">
            <i class="fa-regular fa-calendar me-1"></i>
            <?php echo $fecha_hoy; ?>
        </span>
        <a href="<?php echo Url::to('/referencias/create'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-plus"></i> Nueva Referencia
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <?php if (empty($referencias)): ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-share-from-square fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay referencias registradas.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Fecha</th>
                                    <th>Paciente</th>
                                    <th>Medico Solicitante</th>
                                    <th>Medico Referido</th>
                                    <th>Tipo de Estudio</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($referencias as $ref): 
                                    $esExterno = !empty($ref['medico_referido_externo_id']);
                                    $referidoNombre = $esExterno 
                                        ? 'Dr(a). ' . $ref['ref_ext_nombre'] . ' ' . $ref['ref_ext_apellido'] . ' <small class="text-muted">(Externo)</small>'
                                        : 'Dr(a). ' . $ref['referido_nombre'] . ' ' . $ref['referido_apellido'];
                                    $puedeResponder = !$esExterno && $ref['estado'] === 'Pendiente' && $ref['medico_referido_id'] == $currentUserId;
                                    $puedeCambiar = $esExterno && $ref['estado'] !== 'Completada' && (
                                        $currentRoleId == 1 || $currentRoleId == 2 || $currentRoleId == 3 || $ref['created_by'] == $currentUserId
                                    );
                                ?>
                                    <tr>
                                        <td><span class="badge bg-light text-dark"><?php echo htmlspecialchars($ref['codigo_referencia']); ?></span></td>
                                        <td><?php echo date('d/m/Y', strtotime($ref['fecha_referencia'])); ?></td>
                                        <td><?php echo htmlspecialchars($ref['paciente_nombre'] . ' ' . $ref['paciente_apellido']); ?></td>
                                        <td><?php echo htmlspecialchars('Dr(a). ' . $ref['solicitante_nombre'] . ' ' . $ref['solicitante_apellido']); ?></td>
                                        <td><?php echo $referidoNombre; ?></td>
                                        <td><?php echo htmlspecialchars($ref['tipo_estudio']); ?></td>
                                        <td>
                                            <?php
                                            $estadoClase = '';
                                            switch ($ref['estado']) {
                                                case 'Pendiente': $estadoClase = 'bg-warning text-dark'; break;
                                                case 'Aceptada': $estadoClase = 'bg-success'; break;
                                                case 'Rechazada': $estadoClase = 'bg-danger'; break;
                                                case 'Completada': $estadoClase = 'bg-primary'; break;
                                            }
                                            ?>
                                            <span class="badge <?php echo $estadoClase; ?>"><?php echo htmlspecialchars($ref['estado']); ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="<?php echo Url::to('/referencias/show?id=' . $ref['id']); ?>" class="btn btn-sm btn-apple btn-apple-secondary" title="Ver detalle">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <?php if ($puedeResponder): ?>
                                                <a href="<?php echo Url::to('/referencias/responder?id=' . $ref['id']); ?>" class="btn btn-sm btn-apple btn-apple-primary" title="Responder">
                                                    <i class="fa-solid fa-reply"></i>
                                                </a>
                                                <?php elseif ($puedeCambiar): ?>
                                                <a href="<?php echo Url::to('/referencias/cambiar-estado?id=' . $ref['id']); ?>" class="btn btn-sm btn-outline-warning" title="Cambiar Estado">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
