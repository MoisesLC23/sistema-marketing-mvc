</main>

  <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold">Confirmar salida</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center py-4">
          <p class="text-muted mb-0">¿Estás seguro que deseas cerrar tu sesión?</p>
        </div>
        <div class="modal-footer border-0 justify-content-center">
          <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancelar</button>
          <a href="/marketing/public/logout" class="btn btn-danger px-4">Cerrar Sesión</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      
      const sidebar = document.getElementById("sidebar");
      const btnToggle = document.getElementById("btnToggleSidebar");
      const mobileToggle = document.getElementById("mobileToggle");
      const btnLogout = document.getElementById("btnLogout");

      if (btnToggle && sidebar) {
        if (localStorage.getItem("sidebar-collapsed") === "true") {
          sidebar.classList.add("collapsed");
        }

        btnToggle.addEventListener("click", function() {
          sidebar.classList.toggle("collapsed");
          localStorage.setItem("sidebar-collapsed", sidebar.classList.contains("collapsed"));
        });
      }

      if (mobileToggle && sidebar) {
        mobileToggle.addEventListener("click", function() {
          sidebar.classList.toggle("show");
          const icon = mobileToggle.querySelector("i");
          if (sidebar.classList.contains("show")) {
            icon.classList.replace("bi-list", "bi-x-lg");
          } else {
            icon.classList.replace("bi-x-lg", "bi-list");
          }
        });

        document.addEventListener("click", function(e) {
          if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target) && sidebar.classList.contains("show")) {
            sidebar.classList.remove("show");
            mobileToggle.querySelector("i").classList.replace("bi-x-lg", "bi-list");
          }
        });
      }

      if (btnLogout) {
        btnLogout.addEventListener("click", function() {
          var modal = new bootstrap.Modal(document.getElementById('logoutModal'));
          modal.show();
        });
      }
    });
  </script>
</body>
</html>