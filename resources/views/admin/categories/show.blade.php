<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailsModalLabel">Détails de la catégorie</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-4">
          <img id="detailsImage" src="" alt="" class="img-fluid rounded" style="max-height: 200px;">
        </div>
        <div class="row g-3">
          <div class="col-12">
            <h4 id="detailsName"></h4>
            <span id="detailsBadge" class="badge"></span>
          </div>
          <div class="col-12">
            <h6>Description</h6>
            <p id="detailsDescription" class="text-muted"></p>
          </div>
          <div class="col-12" id="detailsReasonContainer" style="display: none;">
            <h6>Raison de rejet</h6>
            <p id="detailsReason" class="text-muted"></p>
          </div>
          <div class="col-md-6">
            <h6>Approuvé le</h6>
            <p id="detailsApprovedAt" class="text-muted"></p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary" onclick="editCategoryFromDetails()">Modifier</button>
      </div>
    </div>
  </div>
</div>
