<?php
$title = "Importar Medicos Referidos";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><?php echo htmlspecialchars($title); ?></h2>
        <p class="text-muted small mb-0">Cargar medicos externos desde un archivo CSV</p>
    </div>
    <a href="<?php echo Url::to('/medicos-referidos'); ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo Url::to('/medicos-referidos/procesar-importacion'); ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Archivo CSV *</label>
                        <input type="file" name="archivo_csv" class="form-control" accept=".csv" required>
                        <small class="text-muted">Archivo separado por punto y coma (;)</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-apple btn-apple-primary">
                            <i class="fa-solid fa-upload"></i> Importar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa-solid fa-circle-info"></i> Formato del archivo</h5>
            </div>
            <div class="card-body">
                <p class="fw-bold mb-2">Columnas (delimitador <code>;</code>):</p>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Columna</th>
                            <th>Requerido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>1</td><td>Nombre</td><td><span class="badge bg-danger">Si</span></td></tr>
                        <tr><td>2</td><td>Email</td><td><span class="badge bg-danger">Si</span></td></tr>
                        <tr><td>3</td><td>Telefono</td><td><span class="badge bg-secondary">No</span></td></tr>
                        <tr><td>4</td><td>Especialidad</td><td><span class="badge bg-secondary">No</span></td></tr>
                        <tr><td>5</td><td>Institucion</td><td><span class="badge bg-secondary">No</span></td></tr>
                    </tbody>
                </table>

                <p class="fw-bold mb-2 mt-3">Ejemplo:</p>
                <pre class="bg-light p-2 rounded small"><code>Nombre;Email;Telefono;Especialidad;Institucion
Carlos Martinez;carlos@hospital.com;+54 11 4567-8900;Cardiologo;Hospital Italiano
Maria Lopez;maria@clinica.org;;Radiologa;Clinica del Sol
Juan Ramirez;juan@medcenter.com;+54 351 222-3333;;MedCenter</code></pre>

                <small class="text-muted">
                    <i class="fa-solid fa-info-circle"></i> 
                    La primera linea con los nombres de columna se ignora. 
                    El nombre se divide en nombre y apellido usando el primer espacio.
                </small>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
