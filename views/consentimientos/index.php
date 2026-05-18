<?php
$title = "Consentimientos";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h1 class="page-title mb-0">Consentimientos</h1>
    </div>
    <div class="page-header-actions">
        <a href="<?php echo Url::to('/consentimientos/create'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-plus"></i> Nuevo Consentimiento
        </a>
        <a href="<?php echo Url::to('/consentimientos/catalogo'); ?>" class="btn btn-apple btn-apple-secondary">
            <i class="fa-solid fa-book"></i> Catálogo
        </a>
    </div>
</div>

<?php if (Session::get('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo Session::get('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php Session::remove('success'); ?>
<?php endif; ?>

<?php if (Session::get('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo Session::get('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php Session::remove('error'); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="search" class="form-control" id="searchInput" placeholder="Buscar consentimientos..." autocomplete="off">
    </div>
    <div class="text-muted" id="resultsInfo" style="font-size: 13px;"></div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="consentimientosTbody">
                    <tr id="loadingRow">
                        <td colspan="6" class="text-center text-muted py-4">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            Cargando...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<nav id="paginationNav" class="d-flex justify-content-center mt-3"></nav>

<script>
(function() {
    var searchInput = document.getElementById('searchInput');
    var tbody = document.getElementById('consentimientosTbody');
    var paginationNav = document.getElementById('paginationNav');
    var resultsInfo = document.getElementById('resultsInfo');
    var currentPage = 1;
    var currentSearch = '';
    var debounceTimer = null;

    function fetchConsentimientos(page, search) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">'
            + '<div class="spinner-border spinner-border-sm me-2" role="status"></div>'
            + 'Cargando...</td></tr>';

        var url = '<?php echo Url::to("/consentimientos/search"); ?>?page=' + page;
        if (search) {
            url += '&q=' + encodeURIComponent(search);
        }

        fetch(url)
            .then(function(response) {
                if (!response.ok) throw new Error('Error de red');
                return response.json();
            })
            .then(function(result) {
                renderRows(result.data);
                renderPagination(result.totalPages, result.page, result.total);
                renderResultsInfo(result.total, result.page, result.perPage);
                currentPage = result.page;
            })
            .catch(function(error) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-4">'
                    + '<i class="fa-solid fa-triangle-exclamation me-2"></i>'
                    + 'Error al cargar los consentimientos.</td></tr>';
                paginationNav.innerHTML = '';
                resultsInfo.innerHTML = '';
            });
    }

    function estadoBadgeClass(estado) {
        switch (estado) {
            case 'Completado': return 'success';
            case 'Parcialmente Firmado': return 'warning';
            case 'Revocado': return 'danger';
            default: return 'info';
        }
    }

    function formatDate(dateStr) {
        var d = new Date(dateStr);
        var day = String(d.getDate()).padStart(2, '0');
        var month = String(d.getMonth() + 1).padStart(2, '0');
        var year = d.getFullYear();
        var hours = String(d.getHours()).padStart(2, '0');
        var mins = String(d.getMinutes()).padStart(2, '0');
        return day + '/' + month + '/' + year + ' ' + hours + ':' + mins;
    }

    function renderRows(data) {
        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay consentimientos registrados</td></tr>';
            return;
        }

        var html = '';
        for (var i = 0; i < data.length; i++) {
            var c = data[i];
            var badgeClass = estadoBadgeClass(c.estado);
            var showUrl = '<?php echo Url::to("/consentimientos/show"); ?>?id=' + c.id;
            var firmarUrl = '<?php echo Url::to("/consentimientos/firmar"); ?>?id=' + c.id;
            var printUrl = '<?php echo Url::to("/consentimientos/print"); ?>?id=' + c.id;
            var nombreDoc = escapeHtml(c.nombre_documento);
            var version = escapeHtml(c.version || '');
            var paciente = escapeHtml((c.paciente_nombre || '') + ' ' + (c.paciente_apellido || ''));
            var medico = escapeHtml((c.medico_nombre || '') + ' ' + (c.medico_apellido || ''));
            var estado = escapeHtml(c.estado);

            html += '<tr>';
            html += '<td><strong>' + nombreDoc + '</strong>';
            if (version) {
                html += '<small class="text-muted d-block">' + version + '</small>';
            }
            html += '</td>';
            html += '<td>' + paciente + '</td>';
            html += '<td>' + medico + '</td>';
            html += '<td>' + formatDate(c.fecha_generacion) + '</td>';
            html += '<td><span class="badge bg-' + badgeClass + '">' + estado + '</span></td>';
            html += '<td><div class="btn-group btn-group-sm">';
            html += '<a href="' + showUrl + '" class="btn btn-apple btn-apple-secondary" title="Ver"><i class="fa-solid fa-eye"></i></a>';
            if (c.estado !== 'Completado' && c.estado !== 'Revocado') {
                html += '<a href="' + firmarUrl + '" class="btn btn-apple btn-apple-secondary" title="Firmar"><i class="fa-solid fa-pen-to-square"></i></a>';
            }
            html += '<a href="' + printUrl + '" class="btn btn-apple btn-apple-secondary" target="_blank" title="PDF"><i class="fa-solid fa-print"></i></a>';
            html += '</div></td>';
            html += '</tr>';
        }
        tbody.innerHTML = html;
    }

    function renderPagination(totalPages, page, total) {
        if (totalPages <= 1) {
            paginationNav.innerHTML = '';
            return;
        }

        var html = '<ul class="pagination">';

        html += '<li class="page-item' + (page <= 1 ? ' disabled' : '') + '">';
        html += '<a class="page-link" href="#" data-page="' + (page - 1) + '" aria-label="Anterior">&laquo;</a>';
        html += '</li>';

        var start = Math.max(1, page - 2);
        var end = Math.min(totalPages, page + 2);

        if (start > 1) {
            html += '<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>';
            if (start > 2) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        for (var i = start; i <= end; i++) {
            html += '<li class="page-item' + (i === page ? ' active' : '') + '">';
            html += '<a class="page-link" href="#" data-page="' + i + '">' + i + '</a>';
            html += '</li>';
        }

        if (end < totalPages) {
            if (end < totalPages - 1) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            html += '<li class="page-item"><a class="page-link" href="#" data-page="' + totalPages + '">' + totalPages + '</a></li>';
        }

        html += '<li class="page-item' + (page >= totalPages ? ' disabled' : '') + '">';
        html += '<a class="page-link" href="#" data-page="' + (page + 1) + '" aria-label="Siguiente">&raquo;</a>';
        html += '</li>';

        html += '</ul>';
        paginationNav.innerHTML = html;
    }

    function renderResultsInfo(total, page, perPage) {
        if (total === 0) {
            resultsInfo.innerHTML = '';
            return;
        }
        var start = (page - 1) * perPage + 1;
        var end = Math.min(page * perPage, total);
        resultsInfo.innerHTML = 'Mostrando ' + start + '-' + end + ' de ' + total + ' consentimientos';
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        currentSearch = this.value.trim();
        debounceTimer = setTimeout(function() {
            currentPage = 1;
            fetchConsentimientos(1, currentSearch);
        }, 300);
    });

    paginationNav.addEventListener('click', function(e) {
        e.preventDefault();
        var link = e.target.closest('.page-link');
        if (!link) return;
        var page = parseInt(link.getAttribute('data-page'));
        if (page && !isNaN(page)) {
            fetchConsentimientos(page, currentSearch);
        }
    });

    fetchConsentimientos(1, '');
})();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
