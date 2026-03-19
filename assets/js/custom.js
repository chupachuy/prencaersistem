/* assets/js/custom.js */
document.addEventListener("DOMContentLoaded", function () {
    // Tooltips initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Fade out alerts after 5 seconds
    setTimeout(function () {
        var alerts = document.querySelectorAll('.alert-auto-dismiss');
        alerts.forEach(function (alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
