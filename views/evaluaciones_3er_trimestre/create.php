<?php
$title = "Nueva Evaluación 3er Trimestre";
$meses=['','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
$fh=date('j').' de '.$meses[date('n')].' del '.date('Y');
require_once __DIR__.'/../layouts/header.php';
require_once __DIR__.'/../layouts/sidebar.php';
?>
<div class="page-header"><div class="d-flex align-items-center gap-3">
<a href="<?php echo Url::to('/evaluaciones_3er_trimestre');?>" class="btn btn-apple btn-apple-secondary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
<h1 class="page-title mb-0">Nueva Evaluación 3er Trimestre</h1></div>
<div class="page-header-actions"><span class="text-muted"><i class="fa-regular fa-calendar me-1"></i><?php echo $fh;?></span></div></div>
<form action="<?php echo Url::to('/evaluaciones_3er_trimestre/store');?>" method="POST">
<input type="hidden" name="codigo_reporte" value="<?php echo htmlspecialchars($codigo_reporte);?>">

<!-- Datos Generales -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-id-card me-2"></i> Datos Generales</div><div class="card-body">
<div class="row">
<div class="col-md-3 mb-3"><label class="form-label">Código</label><input type="text" class="form-control" value="<?php echo htmlspecialchars($codigo_reporte);?>" readonly></div>
<div class="col-md-3 mb-3"><label for="fecha_evaluacion" class="form-label">Fecha Evaluación *</label><input type="date" class="form-control" name="fecha_evaluacion" value="<?php echo date('Y-m-d');?>" required></div>
<div class="col-md-3 mb-3"><label for="fecha_estudio" class="form-label">Fecha Estudio</label><input type="date" class="form-control" name="fecha_estudio"></div>
<div class="col-md-3 mb-3"><label for="estado" class="form-label">Estado</label><select class="form-select" name="estado"><?php foreach(['Pendiente','En proceso','Completado','Archivado'] as $o):?><option value="<?php echo $o;?>"><?php echo $o;?></option><?php endforeach;?></select></div>
</div>
<div class="row">
<div class="col-md-6 mb-3"><label for="paciente_id" class="form-label">Paciente *</label><select class="form-select" name="paciente_id" required><option value="">Seleccione</option><?php foreach($pacientes as $p):?><option value="<?php echo $p['id'];?>" <?php echo ($paciente_id==$p['id'])?'selected':'';?>><?php echo htmlspecialchars($p['nombre'].' '.$p['apellido']);?></option><?php endforeach;?></select></div>
<div class="col-md-6 mb-3"><label for="medico_id" class="form-label">Médico *</label><select class="form-select" name="medico_id" required><option value="">Seleccione</option><?php foreach($medicos as $m):?><option value="<?php echo $m['id'];?>"><?php echo htmlspecialchars($m['nombre'].' '.$m['apellido'].($m['especialidad']?' - '.$m['especialidad']:''));?></option><?php endforeach;?></select></div>
</div>
</div></div>

<!-- Signos Vitales y Estática Fetal -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-heart-pulse me-2"></i> Signos Vitales y Estática Fetal</div><div class="card-body">
<div class="row">
<div class="col-md-3 mb-3"><label for="edad_gestacional_semanas" class="form-label">Edad Gestacional (sem)</label><input type="number" step="0.1" class="form-control" name="edad_gestacional_semanas" placeholder="Ej: 32.0"></div>
<div class="col-md-3 mb-3"><label for="peso_kg" class="form-label">Peso Materno (kg)</label><input type="number" step="0.01" class="form-control" name="peso_kg" placeholder="Ej: 78.5"></div>
<div class="col-md-3 mb-3"><label for="ta_sistolica" class="form-label">TA Sistólica</label><input type="number" class="form-control" name="ta_sistolica" placeholder="Ej: 120"></div>
<div class="col-md-3 mb-3"><label for="ta_diastolica" class="form-label">TA Diastólica</label><input type="number" class="form-control" name="ta_diastolica" placeholder="Ej: 80"></div>
</div>
<div class="row">
<div class="col-md-3 mb-3"><label for="fcf_lpm" class="form-label">FCF (lpm)</label><input type="number" class="form-control" name="fcf_lpm" placeholder="Ej: 140"></div>
<div class="col-md-3 mb-3"><label for="situacion_fetal" class="form-label">Situación Fetal</label><select class="form-select" name="situacion_fetal"><option value="">No evaluado</option><option value="Longitudinal">Longitudinal</option><option value="Transversa">Transversa</option></select></div>
<div class="col-md-3 mb-3"><label for="presentacion_fetal" class="form-label">Presentación</label><select class="form-select" name="presentacion_fetal"><option value="">No evaluado</option><option value="Cefalico">Cefálico</option><option value="Pelvico">Pélvico</option></select></div>
<div class="col-md-3 mb-3"><label for="posicion_fetal" class="form-label">Posición Fetal</label><input type="text" class="form-control" name="posicion_fetal" placeholder="Dorso anterior/posterior"></div>
</div>
</div></div>

<!-- Antecedentes -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Antecedentes del 3er Trimestre</div><div class="card-body">
<div class="row">
<div class="col-md-4 mb-3"><label for="curva_tolerancia_glucosa" class="form-label">Curva Tolerancia Glucosa</label><select class="form-select" name="curva_tolerancia_glucosa"><option value="No realizada">No realizada</option><option value="Normal">Normal</option><option value="Alterada">Alterada</option></select></div>
<div class="col-md-4 mb-3"><label class="form-label">&nbsp;</label><div class="form-check"><input class="form-check-input" type="checkbox" name="diabetes_gestacional_actual"><label class="form-check-label">Diabetes Gestacional Actual</label></div></div>
<div class="col-md-4 mb-3"><label for="movimientos_fetales" class="form-label">Movimientos Fetales</label><select class="form-select" name="movimientos_fetales"><option value="Normales">Normales</option><option value="Disminuidos">Disminuidos</option></select></div>
</div>
<div class="row">
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="signos_amenaza_parto_pretermino"><label class="form-check-label">Signos Amenaza Parto Pretérmino</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="plan_nacimiento_definido"><label class="form-check-label">Plan de Nacimiento Definido</label></div></div>
</div>
</div></div>

<!-- Crecimiento y RCIU -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-weight-scale me-2"></i> Crecimiento y RCIU</div><div class="card-body">
<div class="row">
<div class="col-md-3 mb-3"><label for="peso_fetal_estimado_gr" class="form-label">Peso Fetal Estimado (gr)</label><input type="number" class="form-control" name="peso_fetal_estimado_gr" placeholder="Ej: 2100"></div>
<div class="col-md-3 mb-3"><label for="percentil_ajustado" class="form-label">Percentil Ajustado</label><input type="number" class="form-control" name="percentil_ajustado" placeholder="Ej: 35"></div>
<div class="col-md-3 mb-3"><label for="clasificacion_crecimiento" class="form-label">Clasificación</label><select class="form-select" name="clasificacion_crecimiento"><option value="">No evaluado</option><option value="Adecuado">Adecuado</option><option value="Mayor a lo esperado">Mayor a lo esperado</option><option value="Menor a lo esperado">Menor a lo esperado</option></select></div>
<div class="col-md-3 mb-3"><label for="estadio_rciu_barcelona" class="form-label">RCIU Barcelona</label><select class="form-select" name="estadio_rciu_barcelona"><option value="Ninguno">Ninguno</option><option value="Estadio I">Estadio I</option><option value="Estadio II">Estadio II</option><option value="Estadio III">Estadio III</option><option value="Estadio IV">Estadio IV</option></select></div>
</div>
</div></div>

<!-- Doppler -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-wave-square me-2"></i> Doppler / Hemodinamia</div><div class="card-body">
<div class="row">
<div class="col-md-3 mb-3"><label for="au_pi" class="form-label">AU PI</label><input type="number" step="0.01" class="form-control" name="au_pi" placeholder="A. Umbilical"></div>
<div class="col-md-3 mb-3"><label for="au_flujo_diastolico" class="form-label">Flujo Diastólico AU</label><select class="form-select" name="au_flujo_diastolico"><option value="">No evaluado</option><option value="Presente">Presente</option><option value="Ausente">Ausente</option><option value="Reverso">Reverso</option></select></div>
<div class="col-md-3 mb-3"><label for="acm_pi" class="form-label">ACM PI</label><input type="number" step="0.01" class="form-control" name="acm_pi" placeholder="A. Cerebral Media"></div>
<div class="col-md-3 mb-3"><label for="dv_onda_a" class="form-label">DV Onda A</label><select class="form-select" name="dv_onda_a"><option value="">No evaluado</option><option value="Positiva">Positiva</option><option value="Ausente">Ausente</option><option value="Reversa">Reversa</option></select></div>
</div>
<div class="row">
<div class="col-md-3 mb-3"><label for="uta_pi_promedio" class="form-label">UTA PI Promedio</label><input type="number" step="0.01" class="form-control" name="uta_pi_promedio" placeholder="A. Uterinas"></div>
<div class="col-md-3 mb-3"><label for="ratio_cu_icp" class="form-label">Ratio CU/ICP</label><input type="number" step="0.01" class="form-control" name="ratio_cu_icp" placeholder="Cerebro-placentario"></div>
<div class="col-md-3 mb-3"><label class="form-label">&nbsp;</label><div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="alteracion_doppler_detectada"><label class="form-check-label">Alteración Doppler Detectada</label></div></div>
</div>
</div></div>

<!-- Anatomía y Líquido -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-droplet me-2"></i> Anatomía y Líquido Amniótico</div><div class="card-body">
<div class="row">
<div class="col-md-3 mb-3"><label for="circular_cordon_cuello" class="form-label">Circular Cordón</label><select class="form-select" name="circular_cordon_cuello"><option value="Negativo">Negativo</option><option value="Simple">Simple</option><option value="Doble">Doble</option></select></div>
<div class="col-md-3 mb-3"><label for="liquido_amniotico_mm" class="form-label">Líquido Amniótico (mm)</label><input type="number" class="form-control" name="liquido_amniotico_mm" placeholder="Ej: 120"></div>
<div class="col-md-3 mb-3"><label for="metodo_medicion_liquido" class="form-label">Método Medición</label><select class="form-select" name="metodo_medicion_liquido"><option value="Bolsillo Maximo">Bolsillo Máximo</option><option value="Phelan">Phelan</option></select></div>
<div class="col-md-3 mb-3"><label for="diagnostico_liquido" class="form-label">Diagnóstico Líquido</label><select class="form-select" name="diagnostico_liquido"><option value="Normal">Normal</option><option value="Oligohidramnios">Oligohidramnios</option><option value="Polihidramnios">Polihidramnios</option></select></div>
</div>
<div class="row"><div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="estructuras_normales" checked><label class="form-check-label">Estructuras Generales Normales</label></div></div></div>
</div></div>

<!-- Evaluación Placentaria -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-uterus me-2"></i> Evaluación Placentaria (PAS)</div><div class="card-body">
<div class="row">
<div class="col-md-4 mb-3"><label for="distancia_oci_mm" class="form-label">Distancia OCI (mm)</label><input type="number" step="0.01" class="form-control" name="distancia_oci_mm" placeholder="Ej: 30.0"></div>
<div class="col-md-4 mb-3"><label for="grosor_placentario_mm" class="form-label">Grosor Placentario (mm)</label><input type="number" class="form-control" name="grosor_placentario_mm" placeholder="Ej: 35"></div>
<div class="col-md-4 mb-3"><label for="grado_madurez" class="form-label">Grado Madurez</label><select class="form-select" name="grado_madurez"><option value="">No evaluado</option><option value="Grado 0-1">Grado 0-1</option><option value="Grado 2">Grado 2</option><option value="Grado 3">Grado 3</option></select></div>
</div>
<div class="row">
<div class="col-md-4 mb-3"><label for="lagunas_vasculares" class="form-label">Lagunas Vasculares</label><select class="form-select" name="lagunas_vasculares"><option value="Ausentes/minimas">Ausentes/mínimas</option><option value="Si">Sí</option><option value="Extensas">Extensas</option></select></div>
<div class="col-md-4 mb-3"><label for="interfase_miometrial" class="form-label">Interfase Miometrial</label><select class="form-select" name="interfase_miometrial"><option value="Intacta">Intacta</option><option value="Adelgazada">Adelgazada</option><option value="Discontinua">Discontinua</option></select></div>
<div class="col-md-4 mb-3"><label for="acretismo_figo_pas" class="form-label">Acretismo FIGO (PAS)</label><select class="form-select" name="acretismo_figo_pas"><option value="Grado 0">Grado 0 - Normal</option><option value="Grado 1">Grado 1</option><option value="Grado 2">Grado 2</option><option value="Grado 3">Grado 3</option></select></div>
</div>
<div class="row"><div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="vasos_puente"><label class="form-check-label">Vasos Puente</label></div></div></div>
</div></div>

<!-- Historial Clínico -->
<div class="card mb-4"><div class="card-header"><i class="fa-solid fa-notes-medical me-2"></i> Historial Clínico</div><div class="card-body"><div class="row">
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="hipertension_cronica" <?php echo (!empty($historial)&&$historial['hipertension_cronica'])?'checked':'';?>><label class="form-check-label">Hipertensión Crónica</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="diabetes" <?php echo (!empty($historial)&&$historial['diabetes'])?'checked':'';?>><label class="form-check-label">Diabetes</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="lupus_les" <?php echo (!empty($historial)&&$historial['lupus_les'])?'checked':'';?>><label class="form-check-label">Lupus / LES</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="sindrome_antifosfolipido_saf" <?php echo (!empty($historial)&&$historial['sindrome_antifosfolipido_saf'])?'checked':'';?>><label class="form-check-label">Síndrome Antifosfolípido</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedente_preeclampsia_rciu" <?php echo (!empty($historial)&&$historial['antecedente_preeclampsia_rciu'])?'checked':'';?>><label class="form-check-label">Ant. Preeclampsia/RCIU</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="fertilizacion_in_vitro" <?php echo (!empty($historial)&&$historial['fertilizacion_in_vitro'])?'checked':'';?>><label class="form-check-label">Fertilización In Vitro</label></div></div>
<div class="col-md-4 mb-2"><div class="form-check"><input class="form-check-input" type="checkbox" name="antecedente_parto_pretermino" <?php echo (!empty($historial)&&$historial['antecedente_parto_pretermino'])?'checked':'';?>><label class="form-check-label">Ant. Parto Pretérmino</label></div></div>
</div></div></div>

<div class="d-flex justify-content-end gap-2 mb-4">
<a href="<?php echo Url::to('/evaluaciones_3er_trimestre');?>" class="btn btn-apple btn-apple-secondary">Cancelar</a>
<button type="submit" class="btn btn-apple btn-apple-primary btn-lg"><i class="fa-solid fa-save"></i> Guardar Evaluación</button></div>
</form>
<?php require_once __DIR__.'/../layouts/footer.php';?>
