@extends('layouts.admin')

@section('title', 'Gestion des Catégories')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="fs-3 mb-1">Gestion des Catégories</h1>
        <p class="mb-0">Gérez vos catégories de produits de manière efficace</p>
      </div>
      <div>
        <button type="button" class="btn btn-primary" onclick="openAddCategoryModal()">
          <i class="ti ti-plus me-2"></i>Ajouter une catégorie
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Search and Filter Bar --}}
<div class="row mb-4">
  <div class="col-12">
    <div class="d-flex gap-2 mb-3 flex-wrap justify-content-between">
      <div class="d-flex gap-2 flex-wrap">
        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par nom ou description..." style="max-width: 300px;">
        <div class="d-flex gap-2">
          <button class="btn btn-outline-secondary filter-btn active" data-status="all">
            Toutes
          </button>
          <button class="btn btn-outline-warning filter-btn" data-status="pending">
            En attente
          </button>
          <button class="btn btn-outline-success filter-btn" data-status="approved">
            Approuvée
          </button>
          <button class="btn btn-outline-danger filter-btn" data-status="rejected">
            Rejetée
          </button>
        </div>
      </div>
      <div class="text-muted small">
        <span id="totalCount">0</span> catégorie<span id="pluralIndicator">s</span> trouvée<span id="pluralIndicator2">s</span>
      </div>
    </div>
  </div>
</div>

{{-- Loading Skeleton --}}
<div class="row" id="loadingSkeleton" style="display: none;">
  <div class="col-12">
    <div class="row g-3">
      <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card animate-pulse">
          <div class="card-img-top bg-light" style="height: 150px;"></div>
          <div class="card-body">
            <div class="bg-light h-4 rounded mb-2"></div>
            <div class="bg-light h-3 rounded w-75 mb-3"></div>
            <div class="d-flex gap-2">
              <div class="bg-light h-8 rounded flex-fill"></div>
              <div class="bg-light h-8 rounded flex-fill"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Categories Grid --}}
<div class="row g-3" id="categoriesGrid">
  {{-- Categories will be loaded here --}}
</div>

{{-- Empty State --}}
<div class="row" id="emptyState" style="display: none;">
  <div class="col-12">
    <div class="text-center py-5">
      <div class="mb-4">
        <i class="ti ti-box-seam fs-1 text-muted"></i>
      </div>
      <h4 class="text-muted">Aucune catégorie trouvée</h4>
      <p class="text-muted mb-4">Commencez par créer votre première catégorie</p>
      <button type="button" class="btn btn-primary" onclick="openAddCategoryModal()">
        <i class="ti ti-plus me-2"></i>Créer une catégorie
      </button>
    </div>
  </div>
</div>

{{-- Pagination --}}
<div class="row" id="paginationContainer" style="display: none;">
  <div class="col-12">
    <nav aria-label="Pagination">
      <ul class="pagination justify-content-center" id="pagination">
        {{-- Pagination will be loaded here --}}
      </ul>
    </nav>
  </div>
</div>

{{-- Add/Edit Category Modal --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryModalLabel">Ajouter une catégorie</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="categoryForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="categoryId" name="category_id">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label for="nomcat" class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nomcat" name="nomcat" placeholder="Ex: Électronique" required>
              <div class="invalid-feedback" id="nomcatError"></div>
            </div>

            <div class="col-12">
              <label for="description" class="form-label">Description de la catégorie</label>
              <textarea class="form-control" id="description" name="description" rows="3" placeholder="Décrivez cette catégorie"></textarea>
              <div class="invalid-feedback" id="descriptionError"></div>
            </div>

            <div class="col-12">
              <label for="image_url" class="form-label">URL de l'image</label>
              <input type="url" class="form-control" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
              <div class="form-text">Entrez l'URL directe de l'image (JPEG, PNG, GIF, WebP). Exemple : https://example.com/image.jpg</div>
              <div class="invalid-feedback" id="imageUrlError"></div>
            </div>

            <div class="col-12">
              <label for="image" class="form-label">Image de la catégorie</label>
              <input type="file" class="form-control" id="image" name="image" accept="image/*">
              <div class="form-text">Formats acceptés: JPEG, PNG, JPG, GIF, WebP. Taille max: 2MB</div>
              <div class="invalid-feedback" id="imageError"></div>
              <div id="imagePreview" class="mt-2" style="display: none;">
                <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeImage()">Supprimer l'image</button>
              </div>
            </div>

            <div class="col-12">
              <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
              <select class="form-select" id="status" name="status" required>
                <option value="">Sélectionnez un statut</option>
                <option value="pending" selected>En attente</option>
                <option value="approved">Approuvée</option>
                <option value="rejected">Rejetée</option>
              </select>
              <div class="invalid-feedback" id="statusError"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary" id="submitBtn">
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none;"></span>
            Enregistrer
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Details Modal --}}
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

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div class="mb-3">
          <i class="ti ti-alert-circle fs-1 text-warning"></i>
        </div>
        <h6>Êtes-vous sûr de vouloir supprimer cette catégorie ?</h6>
        <p class="text-muted mb-0">Cette action est irréversible.</p>
        <p class="fw-bold text-primary mt-2" id="deleteCategoryName"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
          <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none;" id="deleteSpinner"></span>
          Supprimer
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let currentPage = 1;
let currentStatus = 'all';
let currentSearch = '';
let categoryToDelete = null;
let categoryToEdit = null;

function resetCategoryForm() {
    $('#categoryForm')[0].reset();
    $('#categoryId').val('');
    categoryToEdit = null;
    $('#nomcat').removeClass('is-invalid');
    $('#nomcatError').text('');
    $('#description').removeClass('is-invalid');
    $('#descriptionError').text('');
    $('#image_url').removeClass('is-invalid');
    $('#imageUrlError').text('');
    $('#status').removeClass('is-invalid');
    $('#statusError').text('');
    $('#status').val('pending');
    $('#image').val('');
    $('#imageError').text('');
    $('#image_url').val('');
    $('#imagePreview').hide();
    $('#addCategoryModalLabel').text('Ajouter une catégorie');
}

function openAddCategoryModal() {
    resetCategoryForm();
    const modal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
    modal.show();
}

$(document).ready(function() {
    loadCategories();

    // Search functionality
    $('#searchInput').on('input', function() {
        currentSearch = $(this).val();
        currentPage = 1;
        loadCategories();
    });

    // Filter buttons
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        currentStatus = $(this).data('status');
        currentPage = 1;
        loadCategories();
    });

    // Form submission
    $('#categoryForm').on('submit', function(e) {
        e.preventDefault();
        submitForm();
    });

    // Reset form when modal closes
    $('#addCategoryModal').on('hidden.bs.modal', function () {
        resetCategoryForm();
    });

    // Image preview
    $('#image').on('change', function() {
        handleImagePreview(this);
    });

    $('#image_url').on('input', function() {
        handleImageUrlPreview(this.value);
    });
});

function normalizeImageUrl(url) {
    if (!url) {
        return '';
    }

    url = url.trim();

    if (url.match(/^https?:\/\//i)) {
        return url;
    }

    if (url.match(/^\/\//)) {
        return window.location.protocol + url;
    }

    return 'https://' + url.replace(/^\/\//, '');
}

function loadCategories() {
    $('#loadingSkeleton').show();
    $('#categoriesGrid').hide();
    $('#emptyState').hide();
    $('#paginationContainer').hide();

    $.ajax({
        url: '{{ route("admin.category") }}',
        method: 'GET',
        data: {
            search: currentSearch,
            status: currentStatus,
            page: currentPage
        },
        success: function(response) {
            $('#loadingSkeleton').hide();

            if (response.categories && response.categories.length > 0) {
                renderCategories(response.categories);
                renderPagination(response.pagination);
                $('#categoriesGrid').show();
                $('#paginationContainer').show();
            } else {
                $('#emptyState').show();
            }

            updateTotalCount(response.pagination ? response.pagination.total : 0);
        },
        error: function() {
            $('#loadingSkeleton').hide();
            $('#emptyState').show();
            showToast('Erreur lors du chargement des catégories', 'error');
        }
    });
}

function renderCategories(categories) {
    const grid = $('#categoriesGrid');
    grid.empty();

    categories.forEach(category => {
        const statusBadge = getStatusBadge(category.status);
        const imageUrl = getValidImageUrl(category.image);

        const card = `
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 shadow-sm category-card">
                    <div class="position-relative">
                        <img src="${imageUrl}" class="card-img-top" alt="${category.nomcat}" style="height: 150px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2">
                            ${statusBadge}
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold mb-2">${category.nomcat}</h6>
                        <p class="card-text text-muted mb-3" style="min-height: 48px;">${category.description || 'Pas de description'}</p>
                        <div class="mt-auto">
                            <div class="d-flex gap-1 mt-3">
                                <button class="btn btn-sm btn-outline-primary flex-fill" onclick="viewDetails(${category.id})">
                                    <i class="ti ti-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning flex-fill" onclick="editCategory(${category.id})">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger flex-fill" onclick="deleteCategory(${category.id}, '${category.nomcat}')">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        grid.append(card);
    });
}

function renderPagination(pagination) {
    const container = $('#pagination');
    container.empty();

    if (pagination.last_page <= 1) return;

    // Previous button
    const prevDisabled = pagination.current_page === 1 ? 'disabled' : '';
    container.append(`<li class="page-item ${prevDisabled}"><a class="page-link" href="#" onclick="changePage(${pagination.current_page - 1}); return false;">Précédent</a></li>`);

    // Page numbers
    const start = Math.max(1, pagination.current_page - 2);
    const end = Math.min(pagination.last_page, pagination.current_page + 2);

    if (start > 1) {
        container.append(`<li class="page-item"><a class="page-link" href="#" onclick="changePage(1); return false;">1</a></li>`);
        if (start > 2) {
            container.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        }
    }

    for (let i = start; i <= end; i++) {
        const active = i === pagination.current_page ? 'active' : '';
        container.append(`<li class="page-item ${active}"><a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a></li>`);
    }

    if (end < pagination.last_page) {
        if (end < pagination.last_page - 1) {
            container.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        }
        container.append(`<li class="page-item"><a class="page-link" href="#" onclick="changePage(${pagination.last_page}); return false;">${pagination.last_page}</a></li>`);
    }

    // Next button
    const nextDisabled = pagination.current_page === pagination.last_page ? 'disabled' : '';
    container.append(`<li class="page-item ${nextDisabled}"><a class="page-link" href="#" onclick="changePage(${pagination.current_page + 1}); return false;">Suivant</a></li>`);
}

function changePage(page) {
    currentPage = page;
    loadCategories();
    return false;
}

function updateTotalCount(total) {
    $('#totalCount').text(total);
    const plural = total !== 1;
    $('#pluralIndicator').text(plural ? 's' : '');
    $('#pluralIndicator2').text(plural ? 's' : '');
}

function getStatusBadge(status) {
    const badges = {
        'approved': '<span class="badge bg-success">Approuvée</span>',
        'pending': '<span class="badge bg-warning">En attente</span>',
        'rejected': '<span class="badge bg-danger">Rejetée</span>',
        'default': '<span class="badge bg-secondary">Inconnue</span>'
    };
    return badges[status] || badges['default'];
}

function getValidImageUrl(image) {
    if (!image) {
        return '/images/placeholder.png';
    }
    return image.match(/^https?:\/\//i) ? image : `/storage/${image}`;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function editCategory(id) {
    $.ajax({
        url: `/admin/category/${id}`,
        method: 'GET',
        success: function(category) {
            $('#addCategoryModalLabel').text('Modifier la catégorie');
                    $('#categoryId').val(category.id);
            $('#nomcat').val(category.nomcat);
            $('#description').val(category.description || '');
            $('#status').val(category.status);
            $('#image_url').val(category.image && category.image.match(/^https?:\/\//i) ? category.image : '');
            $('#image').val('');

            if (category.image) {
                const previewUrl = getValidImageUrl(category.image);
                $('#previewImg').attr('src', previewUrl);
                $('#imagePreview').show();
            } else {
                $('#imagePreview').hide();
            }

            const modal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
            modal.show();
        },
        error: function() {
            showToast('Erreur lors du chargement de la catégorie', 'error');
        }
    });
}

function editCategoryFromDetails() {
    bootstrap.Modal.getInstance(document.getElementById('detailsModal')).hide();
    if (categoryToEdit) {
        editCategory(categoryToEdit);
    }
}

function viewDetails(id) {
    $.ajax({
        url: `/admin/category/${id}`,
        method: 'GET',
        success: function(category) {
            $('#detailsName').text(category.nomcat);
            $('#detailsBadge').html(getStatusBadge(category.status));
            $('#detailsImage').attr('src', getValidImageUrl(category.image));

            $('#detailsDescription').text(category.description || 'Pas de description disponible');

                if (category.rejection_reason && category.status === 'rejected') {
                $('#detailsReason').text(category.rejection_reason);
                $('#detailsReasonContainer').show();
            } else {
                $('#detailsReasonContainer').hide();
            }

            $('#detailsApprovedAt').text(category.approved_at ? formatDate(category.approved_at) : 'Aucune date');

            categoryToEdit = category.id;

            const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
            modal.show();
        },
        error: function() {
            showToast('Erreur lors du chargement des détails', 'error');
        }
    });
}

function deleteCategory(id, name) {
    categoryToDelete = id;
    $('#deleteCategoryName').text(name);
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function confirmDelete() {
    if (!categoryToDelete) return;

    $('#deleteSpinner').show();

    $.ajax({
        url: `/admin/category/${categoryToDelete}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#deleteSpinner').hide();
            bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
            showToast(response.message, 'success');
            loadCategories();
        },
        error: function() {
            $('#deleteSpinner').hide();
            showToast('Erreur lors de la suppression', 'error');
        },
        complete: function() {
            categoryToDelete = null;
        }
    });
}

function submitForm() {
    const imageUrlInput = $('#image_url');
    const normalizedUrl = normalizeImageUrl(imageUrlInput.val());
    imageUrlInput.val(normalizedUrl);

    const formData = new FormData($('#categoryForm')[0]);
    const isEdit = $('#categoryId').val();

    if (isEdit) {
        formData.append('_method', 'PUT');
    }

    $('#submitBtn').prop('disabled', true);
    $('#submitBtn .spinner-border').show();

    // Clear previous errors
    $('.invalid-feedback').text('');
    $('.form-control, .form-select').removeClass('is-invalid');

    $.ajax({
        url: isEdit ? `/admin/category/${$('#categoryId').val()}` : '{{ route("admin.category.store") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#submitBtn').prop('disabled', false);
            $('#submitBtn .spinner-border').hide();
            bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();
            showToast(response.message, 'success');
            
            // Reset form
            $('#categoryForm')[0].reset();
            $('#categoryId').val('');
            $('#addCategoryModalLabel').text('Ajouter une catégorie');
            $('#imagePreview').hide();
            
            loadCategories();
        },
        error: function(xhr) {
            $('#submitBtn').prop('disabled', false);
            $('#submitBtn .spinner-border').hide();
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    const elementId = field === 'nomcat' ? 'nomcat' : field;
                    const errorId = field === 'nomcat' ? 'nomcatError' : `${field}Error`;
                    $(`#${errorId}`).text(errors[field][0]);
                    $(`#${elementId}`).addClass('is-invalid');
                });
            } else {
                showToast('Erreur lors de l\'enregistrement', 'error');
            }
        }
    });
}

function handleImagePreview(input) {
    if (input.files && input.files[0]) {
        $('#image_url').val('');
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#previewImg').attr('src', e.target.result);
            $('#imagePreview').show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function handleImageUrlPreview(url) {
    const normalizedUrl = normalizeImageUrl(url);

    if (normalizedUrl && normalizedUrl.match(/^https?:\/\//i)) {
        $('#image').val('');
        $('#previewImg').attr('src', normalizedUrl);
        $('#imagePreview').show();
        $('#image_url').val(normalizedUrl);
    } else if (!url) {
        $('#imagePreview').hide();
    }
}

function removeImage() {
    $('#image').val('');
    $('#imagePreview').hide();
}

function showToast(message, type = 'success') {
    const toastClass = type === 'success' ? 'bg-success' : 'bg-danger';
    const toast = `
        <div class="toast align-items-center text-white ${toastClass} border-0 position-fixed top-0 end-0 m-3" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

    $('body').append(toast);
    const toastEl = $('.toast').last();
    const bsToast = new bootstrap.Toast(toastEl);
    bsToast.show();

    toastEl.on('hidden.bs.toast', function() {
        $(this).remove();
    });
}
</script>
@endpush
