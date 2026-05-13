<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createCategoryModalLabel">Ajouter une catégorie</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="createCategoryForm" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label for="create_nomcat" class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="create_nomcat" name="nomcat" placeholder="Ex: Électronique" required>
              <div class="invalid-feedback" id="create_nomcatError"></div>
            </div>

            <div class="col-12">
              <label for="create_description" class="form-label">Description de la catégorie</label>
              <textarea class="form-control" id="create_description" name="description" rows="3" placeholder="Décrivez cette catégorie"></textarea>
              <div class="invalid-feedback" id="create_descriptionError"></div>
            </div>

            <div class="col-12">
              <label for="create_image_url" class="form-label">URL de l'image</label>
              <input type="url" class="form-control" id="create_image_url" name="image_url" placeholder="https://example.com/image.jpg">
              <div class="form-text">Entrez l'URL directe de l'image (JPEG, PNG, GIF, WebP). Exemple : https://example.com/image.jpg</div>
              <div class="invalid-feedback" id="create_imageUrlError"></div>
            </div>

            <div class="col-12">
              <label for="create_image" class="form-label">Image de la catégorie</label>
              <input type="file" class="form-control" id="create_image" name="image" accept="image/*">
              <div class="form-text">Formats acceptés: JPEG, PNG, JPG, GIF, WebP. Taille max: 2MB</div>
              <div class="invalid-feedback" id="create_imageError"></div>
              <div id="create_imagePreview" class="mt-2" style="display: none;">
                <img id="create_previewImg" src="" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeImage('create')">Supprimer l'image</button>
              </div>
            </div>

            <div class="col-12">
              <label for="create_status" class="form-label">Statut <span class="text-danger">*</span></label>
              <select class="form-select" id="create_status" name="status" required>
                <option value="">Sélectionnez un statut</option>
                <option value="pending" selected>En attente</option>
                <option value="approved">Approuvée</option>
                <option value="rejected">Rejetée</option>
              </select>
              <div class="invalid-feedback" id="create_statusError"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary" id="createSubmitBtn">
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none;"></span>
            Enregistrer
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
