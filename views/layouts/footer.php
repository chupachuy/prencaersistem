    </main>
    
    <footer style="background: var(--apple-card); padding: 20px; text-align: center; border-top: 1px solid rgba(0,0,0,0.04); margin-top: auto;">
        <p style="margin: 0; font-size: 13px; color: var(--apple-gray);">&copy; <?php echo date('Y'); ?> PreNacer. Todos los derechos reservados.</p>
    </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sidebarToggle = document.getElementById("sidebarToggle");
        const sidebar = document.getElementById("sidebar");
        
        if(sidebarToggle) {
            sidebarToggle.addEventListener("click", function () {
                sidebar.classList.toggle("show");
            });
        }
    });
</script>

</body>
</html>