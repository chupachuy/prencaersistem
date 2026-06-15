<?php
$title = "Controles Prenatales";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';

function badgeTipo($tipo) {
    $colores = [
        'Propia'  => '#34c759',
        'Referida' => '#367d84',
        'IMSS'    => '#f0ad4e',
        'ISSSTE'  => '#d9534f',
    ];
    $color = $colores[$tipo] ?? 'var(--apple-gray)';
    return "<span class=\"badge\" style=\"background: $color; padding: 6px 12px; border-radius: 20px; font-weight: 500; font-size: 0.8rem;\">" . htmlspecialchars($tipo) . "</span>";
}
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title"><?php echo htmlspecialchars($title); ?></h1>
            <p class="page-subtitle">Seguimiento de pacientes embarazadas: edad gestacional, diagnósticos y fecha probable de parto</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Tipo Seguimiento</th>
                        <th>Diagnósticos</th>
                        <th>Edad Gestacional</th>
                        <th>FPP</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pacientes)): ?>
                        <?php foreach ($pacientes as $p): 
                            $dadaDeAlta = !empty($p['fecha_alta']);
                            $tipo = $p['tipo_seguimiento'] ?? 'Propia';
                            $semanas = $p['semanas_gestacionales'];
                            $fpp = $p['fpp_usg'];
                            $alertar = !$dadaDeAlta && ($tipo === 'IMSS' || $tipo === 'ISSSTE') && $semanas !== null && $semanas >= 36;
                            $rowStyle = $dadaDeAlta ? 'opacity: 0.55;' : '';
                            if ($alertar) $rowStyle .= ' background: #fff3cd;';
                        ?>
                            <tr style="<?php echo $rowStyle; ?>">
                                <td>
                                    <div class="d-flex align-items-center gap-2" style="font-weight: 500;">
                                        <div style="width: 32px; height: 32px; background: var(--apple-bg); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-user" style="color: var(--apple-gray); font-size: 12px;"></i>
                                        </div>
                                        <?php echo htmlspecialchars($p['nombre'] . ' ' . $p['apellido']); ?>
                                        <?php if ($dadaDeAlta): ?>
                                            <span class="badge" style="background: var(--apple-gray); padding: 4px 8px; border-radius: 20px; font-size: 0.7rem;">
                                                Alta: <?php echo (new DateTime($p['fecha_alta']))->format('d/m/Y'); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <form method="POST" action="<?php echo Url::to('/controles-prenatales/update-tipo'); ?>" style="display: inline-flex; align-items: center; gap: 6px;">
                                        <input type="hidden" name="paciente_id" value="<?php echo $p['id']; ?>">
                                        <select name="tipo_seguimiento" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto; min-width: 100px;">
                                            <?php foreach (['Propia', 'Referida', 'IMSS', 'ISSSTE'] as $opcion): ?>
                                                <option value="<?php echo $opcion; ?>" <?php echo $tipo === $opcion ? 'selected' : ''; ?>>
                                                    <?php echo $opcion; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </td>
                                <td style="max-width: 250px;">
                                    <?php if (!empty($p['diagnosticos_activos'])): ?>
                                        <span style="font-size: 0.85rem;"><?php echo htmlspecialchars($p['diagnosticos_activos']); ?></span>
                                    <?php else: ?>
                                        <span style="color: var(--apple-gray);">Sin diagnósticos activos</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($semanas !== null): ?>
                                        <span style="font-weight: 600; font-size: 1.1rem; <?php echo $alertar ? 'color: #d9534f;' : ''; ?>">
                                            <?php echo number_format($semanas, 1); ?> sem
                                        </span>
                                        <?php if ($alertar): ?>
                                            <br><small style="color: #d9534f; font-weight: 600;">
                                                <i class="fa-solid fa-triangle-exclamation"></i> ¡Dar de alta!
                                            </small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span style="color: var(--apple-gray);">Sin evaluación</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($fpp): ?>
                                        <?php echo (new DateTime($fpp))->format('d/m/Y'); ?>
                                    <?php else: ?>
                                        <span style="color: var(--apple-gray);">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($tipo === 'Referida' && !$dadaDeAlta): ?>
                                        <span style="color: var(--apple-blue); font-size: 0.85rem;">
                                            <i class="fa-solid fa-user-doctor"></i> Médico externo
                                        </span>
                                    <?php elseif (!$dadaDeAlta): ?>
                                        <?php if ($alertar): ?>
                                            <form method="POST" action="<?php echo Url::to('/controles-prenatales/alta'); ?>" style="display: inline;">
                                                <input type="hidden" name="paciente_id" value="<?php echo $p['id']; ?>">
                                                <button type="submit" class="btn btn-apple" style="background: #d9534f; color: #fff; border: none; padding: 6px 16px; border-radius: 20px; font-size: 0.85rem; font-weight: 500;" onclick="return confirm('¿Dar de alta a esta paciente?')">
                                                    <i class="fa-solid fa-check-circle"></i> Dar de Alta
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <?php if ($tipo === 'IMSS' || $tipo === 'ISSSTE'): ?>
                                                <span style="color: var(--apple-gray); font-size: 0.8rem;">
                                                    Alta a las 36 sem
                                                </span>
                                            <?php else: ?>
                                                <span style="color: var(--apple-green); font-size: 0.85rem;">
                                                    <i class="fa-solid fa-circle-check"></i> En seguimiento
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span style="color: var(--apple-gray); font-size: 0.8rem;">Dada de alta</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5" style="color: var(--apple-gray);">
                                <i class="fa-solid fa-clipboard-list fa-3x mb-3" style="opacity: 0.3;"></i>
                                <h5 style="font-weight: 600;">No hay pacientes registradas</h5>
                                <p class="mb-0">Comience agregando pacientes al sistema para ver su control prenatal.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.form-select-sm {
    font-size: 0.8rem;
    padding: 4px 28px 4px 10px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    background-color: #fff;
    cursor: pointer;
}
.form-select-sm:focus {
    border-color: var(--apple-blue);
    box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.15);
    outline: none;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
