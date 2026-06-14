<?php
$title = "Ver Reporte 1er Trimestre";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="<?php echo Url::to('/reportes_1er_trimestre'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
        <h1 class="page-title mb-0">Ver Reporte 1er Trimestre</h1>
    </div>
    <div class="page-header-actions">
        <?php if ($reporte['estado'] === 'Completado'): ?>
            <form method="POST" action="<?php echo Url::to('/reportes_1er_trimestre/enviar?id=' . $reporte['id']); ?>" style="display:inline;">
                <select name="destinatario" class="form-select form-select-sm" style="width:auto;display:inline;vertical-align:middle;">
                    <option value="">-- Destinatario --</option>
                    <?php if (!empty($reporte['paciente_email'])): ?><option value="paciente"><?php echo htmlspecialchars($reporte['paciente_nombre'] . ' ' . $reporte['paciente_apellido']); ?> (Paciente)</option><?php endif; ?>
                    <?php if (!empty($reporte['medico_email'])): ?><option value="medico"><?php echo htmlspecialchars($reporte['medico_nombre'] . ' ' . $reporte['medico_apellido']); ?> (Médico)</option><?php endif; ?>
                    <?php if (!empty($reporte['medico_referido_email'])): ?><option value="referido"><?php echo htmlspecialchars($reporte['medico_referido_nombre'] . ' ' . $reporte['medico_referido_apellido']); ?> (Referido)</option><?php endif; ?>
                    <option value="todos">-- Todos --</option>
                </select>
                <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8'); ?>">
                <button type="button" class="btn btn-apple btn-apple-primary" onclick="var f=this.form,d=f.destinatario;if(!d.value){alert('Seleccione un destinatario');return;}if(confirm('¿Enviar a '+d.options[d.selectedIndex].text+'?'))f.submit();">
                    <i class="fa-solid fa-paper-plane"></i> Enviar
                </button>
            </form>
        <?php endif; ?>
        <a href="<?php echo Url::to('/reportes_1er_trimestre/edit?id=' . $reporte['id']); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-edit"></i> Editar
        </a>
        <a href="<?php echo Url::to('/reportes_1er_trimestre/pdf?id=' . $reporte['id']); ?>" class="btn btn-apple btn-apple-secondary" target="_blank">
            <i class="fa-solid fa-download"></i> Imprimir
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-id-card me-2"></i> Datos del Reporte
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Código:</div>
                    <div class="col-md-8"><?php echo htmlspecialchars($reporte['codigo_reporte']); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Fecha:</div>
                    <div class="col-md-8"><?php echo date('d/m/Y', strtotime($reporte['fecha_reporte'])); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Lugar:</div>
                    <div class="col-md-8"><?php echo htmlspecialchars($reporte['lugar'] ?? '-'); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Estado:</div>
                    <div class="col-md-8">
                        <?php 
                        $estadoClass = match($reporte['estado']) {
                            'Completado' => 'success',
                            'En proceso' => 'warning',
                            'Archivado' => 'secondary',
                            default => 'info'
                        };
                        ?>
                        <span class="badge bg-<?php echo $estadoClass; ?>"><?php echo htmlspecialchars($reporte['estado']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-user me-2"></i> Paciente y Médico
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Paciente:</div>
                    <div class="col-md-8"><?php echo htmlspecialchars($reporte['paciente_nombre'] . ' ' . $reporte['paciente_apellido']); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Médico:</div>
                    <div class="col-md-8"><?php echo htmlspecialchars($reporte['medico_nombre'] . ' ' . $reporte['medico_apellido']); ?></div>
                </div>
                <?php if (!empty($reporte['medico_referido_nombre'])): ?>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Médico Referido:</div>
                    <div class="col-md-8"><?php echo htmlspecialchars($reporte['medico_referido_nombre'] . ' ' . $reporte['medico_referido_apellido']); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-heart-pulse me-2"></i> Signos Vitales
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Peso:</div>
                    <div class="col-md-8"><?php echo $reporte['peso'] ? htmlspecialchars($reporte['peso']) . ' kg' : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Talla:</div>
                    <div class="col-md-8"><?php echo $reporte['talla'] ? htmlspecialchars($reporte['talla']) . ' cm' : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Presión Arterial:</div>
                    <div class="col-md-8">
                        <?php 
                        if ($reporte['presion_sistolica'] && $reporte['presion_diastolica']) {
                            echo htmlspecialchars($reporte['presion_sistolica']) . '/' . htmlspecialchars($reporte['presion_diastolica']) . ' mmHg';
                        } else {
                            echo '-';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-person-breastfeeding me-2"></i> Historia Obstétrica
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Gesta <small class="text-muted">(N. de embarazos):</small></div>
                    <div class="col-md-8"><?php echo $reporte['gesta'] ?? '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Para <small class="text-muted">(partos vaginales):</small></div>
                    <div class="col-md-8"><?php echo $reporte['para'] ?? '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Abortos:</div>
                    <div class="col-md-8"><?php echo $reporte['abortos'] ?? '-'; ?></div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-calendar me-2"></i> Fechas Obstétricas
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Fecha Última Regla:</div>
                    <div class="col-md-8"><?php echo $reporte['fecha_ultima_regla'] ? date('d/m/Y', strtotime($reporte['fecha_ultima_regla'])) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Edad Gestacional (FUR):</div>
                    <div class="col-md-8"><?php echo $reporte['edad_gestacional_fum'] ? htmlspecialchars($reporte['edad_gestacional_fum']) . ' semanas' : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">FPP por FUR:</div>
                    <div class="col-md-8"><?php echo $reporte['fecha_probable_parto_fum'] ? date('d/m/Y', strtotime($reporte['fecha_probable_parto_fum'])) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Datos del Equipo:</div>
                    <div class="col-md-8"><?php echo $reporte['equipo_estudio'] ? nl2br(htmlspecialchars($reporte['equipo_estudio'])) : '-'; ?></div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa Ultrasound"></i> Datos Ecográficos
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Longitud Craneo-Cauda:</div>
                    <div class="col-md-8"><?php echo $reporte['longitud_craneo_cauda'] ? htmlspecialchars($reporte['longitud_craneo_cauda']) . ' mm' : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Edad Gestacional (USG):</div>
                    <div class="col-md-8"><?php echo $reporte['edad_gestacional_usg'] ? htmlspecialchars($reporte['edad_gestacional_usg']) . ' semanas' : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Fecha Probable de Parto calculada por Ultrasonido:</div>
                    <div class="col-md-8"><?php echo $reporte['fecha_probable_parto_usg'] ? date('d/m/Y', strtotime($reporte['fecha_probable_parto_usg'])) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Equipo de Ultrasonografía:</div>
                    <div class="col-md-8"><?php echo $reporte['equipo_usg'] ? htmlspecialchars($reporte['equipo_usg']) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Transductor:</div>
                    <div class="col-md-8"><?php echo $reporte['transductor_tipo'] ? htmlspecialchars($reporte['transductor_tipo']) : '-'; ?></div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-baby me-2"></i> Exploración Estructural Fetal
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Cráneo:</div>
                    <div class="col-md-8"><?php echo $reporte['craneo'] ? nl2br(htmlspecialchars($reporte['craneo'])) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Sistema Nervioso Central:</div>
                    <div class="col-md-8"><?php echo $reporte['sistema_nervioso_central'] ? nl2br(htmlspecialchars($reporte['sistema_nervioso_central'])) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Cuello:</div>
                    <div class="col-md-8"><?php echo $reporte['cuello'] ? nl2br(htmlspecialchars($reporte['cuello'])) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Cara:</div>
                    <div class="col-md-8"><?php echo $reporte['cara'] ? nl2br(htmlspecialchars($reporte['cara'])) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Columna:</div>
                    <div class="col-md-8"><?php echo $reporte['columna'] ? nl2br(htmlspecialchars($reporte['columna'])) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Tórax:</div>
                    <div class="col-md-8"><?php echo $reporte['torax'] ? nl2br(htmlspecialchars($reporte['torax'])) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Corazón:</div>
                    <div class="col-md-8"><?php echo $reporte['corazon'] ? nl2br(htmlspecialchars($reporte['corazon'])) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Abdomen:</div>
                    <div class="col-md-8"><?php echo $reporte['abdomen'] ? nl2br(htmlspecialchars($reporte['abdomen'])) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Extremidades:</div>
                    <div class="col-md-8"><?php echo $reporte['extremidades'] ? nl2br(htmlspecialchars($reporte['extremidades'])) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Líquido Amniótico:</div>
                    <div class="col-md-8"><?php echo $reporte['liquido_amniotico'] ? nl2br(htmlspecialchars($reporte['liquido_amniotico'])) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Decidua:</div>
                    <div class="col-md-8"><?php echo $reporte['decidua'] ? nl2br(htmlspecialchars($reporte['decidua'])) : '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Cérvix:</div>
                    <div class="col-md-8"><?php echo $reporte['cervix'] ? nl2br(htmlspecialchars($reporte['cervix'])) : '-'; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
