    </main>
    
    <footer style="background: var(--apple-card); padding: 20px; text-align: center; border-top: 1px solid rgba(0,0,0,0.04); margin-top: auto;">
        <p style="margin: 0; font-size: 13px; color: var(--apple-gray);">&copy; <?php echo date('Y'); ?> PreNacer. Todos los derechos reservados.</p>
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Sidebar toggle
        const sidebarToggle = document.getElementById("sidebarToggle");
        const sidebar = document.getElementById("sidebar");
        if(sidebarToggle) {
            sidebarToggle.addEventListener("click", function () {
                sidebar.classList.toggle("show");
            });
        }

        // Add CSRF token to all POST forms automatically.
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            const token = csrfMeta.getAttribute('content');
            document.querySelectorAll('form[method="post"], form[method="POST"]').forEach(function (form) {
                if (!form.querySelector('input[name="_csrf"]')) {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = '_csrf';
                    hidden.value = token;
                    form.appendChild(hidden);
                }
            });
        }

        // Inicializar tooltips de Bootstrap (iconos de ayuda en formularios)
        const tooltipEls = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipEls.forEach(function(el) {
            new bootstrap.Tooltip(el);
        });
    });
</script>

</body>
</html>