<?php
$title = "Gestión de Usuarios";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Personal y Usuarios</h1>
            <p class="page-subtitle">Administra los usuarios del sistema</p>
        </div>
        <a href="<?php echo Url::to('/usuarios/create'); ?>" class="btn btn-apple btn-apple-primary">
            <i class="fa-solid fa-plus"></i> Nuevo Usuario
        </a>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3 gap-3 flex-wrap">
    <div class="d-flex gap-2 align-items-center">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search" class="form-control" id="searchInput" placeholder="Buscar por nombre, email o especialidad..." autocomplete="off">
        </div>
        <select id="rolFilter" class="form-select" style="width: auto; min-width: 180px;">
            <option value="">Todos los roles</option>
            <?php foreach ($roles as $rol): ?>
                <option value="<?php echo $rol['id']; ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="text-muted" id="resultsInfo" style="font-size: 13px;"></div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Especialidad</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="usuariosTbody">
                    <tr id="loadingRow">
                        <td colspan="8" class="text-center py-4" style="color: var(--apple-gray);">
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
    var rolFilter = document.getElementById('rolFilter');
    var tbody = document.getElementById('usuariosTbody');
    var paginationNav = document.getElementById('paginationNav');
    var resultsInfo = document.getElementById('resultsInfo');
    var currentPage = 1;
    var currentSearch = '';
    var currentRolId = '';
    var debounceTimer = null;

    function fetchUsuarios(page, search, rolId) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4" style="color: var(--apple-gray);">'
            + '<div class="spinner-border spinner-border-sm me-2" role="status"></div>'
            + 'Cargando...</td></tr>';

        var url = '<?php echo Url::to("/usuarios/search"); ?>?page=' + page;
        if (search) {
            url += '&q=' + encodeURIComponent(search);
        }
        if (rolId) {
            url += '&rol_id=' + encodeURIComponent(rolId);
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
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger py-4">'
                    + '<i class="fa-solid fa-triangle-exclamation me-2"></i>'
                    + 'Error al cargar los usuarios.</td></tr>';
                paginationNav.innerHTML = '';
                resultsInfo.innerHTML = '';
            });
    }

    function renderRows(data) {
        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4" style="color: var(--apple-gray);">'
                + '<i class="fa-solid fa-users fa-2x mb-2" style="opacity: 0.3; display: block;"></i>'
                + '<p class="mb-0">No se encontraron usuarios.</p></td></tr>';
            return;
        }

        var html = '';
        for (var i = 0; i < data.length; i++) {
            var u = data[i];
            var nombre = escapeHtml(u.nombre);
            var apellido = escapeHtml(u.apellido);
            var email = escapeHtml(u.email);
            var telefono = escapeHtml(u.telefono || '-');
            var rolNombre = escapeHtml(u.rol_nombre || 'N/A');
            var especialidad = escapeHtml(u.especialidad || '');
            var activo = u.activo == 1;
            var fecha = u.created_at ? formatDate(u.created_at) : 'N/A';
            var inicial1 = (u.nombre || 'U').charAt(0).toUpperCase();
            var inicial2 = (u.apellido || '').charAt(0).toUpperCase();
            var initials = inicial1 + inicial2;
            var editUrl = '<?php echo Url::to("/usuarios/edit"); ?>?id=' + u.id;

            html += '<tr>';
            html += '<td>'
                + '<div class="d-flex align-items-center gap-3">'
                + '<div style="width: 35px; height: 35px; background: var(--apple-blue); color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600;">'
                + initials + '</div>'
                + '<span style="font-weight: 500;">' + nombre + ' ' + apellido + '</span>'
                + '</div>'
                + '</td>';
            html += '<td style="color: var(--apple-gray);">' + email + '</td>';
            html += '<td style="color: var(--apple-gray);">' + telefono + '</td>';
            html += '<td><span class="badge" style="background: #d1e7dd; color: #367d84;">' + rolNombre + '</span></td>';
            html += '<td>';
            if (especialidad) {
                html += '<span class="badge" style="background: #e2e3e5; color: #41464b;">' + especialidad + '</span>';
            } else {
                html += '<span style="color: var(--apple-gray); font-size: 13px;">N/A</span>';
            }
            html += '</td>';
            html += '<td style="color: var(--apple-gray); font-size: 13px;">' + fecha + '</td>';
            html += '<td>';
            if (activo) {
                html += '<span class="badge badge-success">Activo</span>';
            } else {
                html += '<span class="badge badge-danger">Inactivo</span>';
            }
            html += '</td>';
            html += '<td class="text-center"><a href="' + editUrl + '" class="action-btn action-btn-edit" title="Editar"><i class="fa-solid fa-pen-to-square"></i></a></td>';
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
        resultsInfo.innerHTML = 'Mostrando ' + start + '-' + end + ' de ' + total + ' usuarios';
    }

    function formatDate(dateStr) {
        var d = new Date(dateStr);
        var day = String(d.getDate()).padStart(2, '0');
        var month = String(d.getMonth() + 1).padStart(2, '0');
        var year = d.getFullYear();
        return day + '/' + month + '/' + year;
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function doFetch() {
        currentPage = 1;
        fetchUsuarios(1, currentSearch, currentRolId);
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        currentSearch = this.value.trim();
        debounceTimer = setTimeout(doFetch, 300);
    });

    rolFilter.addEventListener('change', function() {
        currentRolId = this.value;
        doFetch();
    });

    paginationNav.addEventListener('click', function(e) {
        e.preventDefault();
        var link = e.target.closest('.page-link');
        if (!link) return;
        var page = parseInt(link.getAttribute('data-page'));
        if (page && !isNaN(page)) {
            fetchUsuarios(page, currentSearch, currentRolId);
        }
    });

    fetchUsuarios(1, '', '');
})();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
