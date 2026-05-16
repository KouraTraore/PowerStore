<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCategoryModalLabel">Modifier la catégorie</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editCategoryForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="editCategoryId" name="category_id">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label for="edit_nomcat" class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="edit_nomcat" name="nomcat" placeholder="Ex: Électronique" required>
              <div class="invalid-feedback" id="edit_nomcatError"></div>
            </div>

            <div class="col-12">
              <label for="edit_description" class="form-label">Description de la catégorie</label>
              <textarea class="form-control" id="edit_description" name="description" rows="3" placeholder="Décrivez cette catégorie"></textarea>
              <div class="invalid-feedback" id="edit_descriptionError"></div>
            </div>

            <div class="col-12">
              <label for="edit_image_url" class="form-label">URL de l'image</label>
              <input type="url" class="form-control" id="edit_image_url" name="image_url" placeholder="https://example.com/image.jpg">
              <div class="form-text">Entrez l'URL directe de l'image (JPEG, PNG, GIF, WebP). Exemple : https://example.com/image.jpg</div>
              <div class="invalid-feedback" id="edit_imageUrlError"></div>
            </div>

            <div class="col-12">
              <label for="edit_image" class="form-label">Image de la catégorie</label>
              <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
              <div class="form-text">Formats acceptés: JPEG, PNG, JPG, GIF, WebP. Taille max: 2MB</div>
              <div class="invalid-feedback" id="edit_imageError"></div>
              <div id="edit_imagePreview" class="mt-2" style="display: none;">
                <img id="edit_previewImg" src="" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeImage('edit')">Supprimer l'image</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary" id="editSubmitBtn">
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none;"></span>
            Enregistrer
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
