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
        <button type="button" class="btn btn-primary" onclick="openCreateCategoryModal()">
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
      <button type="button" class="btn btn-primary" onclick="openCreateCategoryModal()">
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

@include('admin.categories.create')
@include('admin.categories.edit')
@include('admin.categories.show')

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

function resetCreateForm() {
    $('#createCategoryForm')[0].reset();
    $('#create_nomcat').removeClass('is-invalid');
    $('#create_nomcatError').text('');
    $('#create_description').removeClass('is-invalid');
    $('#create_descriptionError').text('');
    $('#create_image_url').removeClass('is-invalid');
    $('#create_imageUrlError').text('');
    $('#create_status').removeClass('is-invalid');
    $('#create_statusError').text('');
    $('#create_status').val('pending');
    $('#create_image').val('');
    $('#create_imageError').text('');
    $('#create_image_url').val('');
    $('#create_imagePreview').hide();
}

function resetEditForm() {
    $('#editCategoryForm')[0].reset();
    $('#editCategoryId').val('');
    $('#edit_nomcat').removeClass('is-invalid');
    $('#edit_nomcatError').text('');
    $('#edit_description').removeClass('is-invalid');
    $('#edit_descriptionError').text('');
    $('#edit_image_url').removeClass('is-invalid');
    $('#edit_imageUrlError').text('');
    $('#edit_status').removeClass('is-invalid');
    $('#edit_statusError').text('');
    $('#edit_image').val('');
    $('#edit_imageError').text('');
    $('#edit_imagePreview').hide();
}

function openCreateCategoryModal() {
    resetCreateForm();
    const modal = new bootstrap.Modal(document.getElementById('createCategoryModal'));
    modal.show();
}

function getInputPrefix(inputId) {
    return inputId.startsWith('edit_') ? 'edit' : 'create';
}

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
    $('#createCategoryForm').on('submit', function(e) {
        e.preventDefault();
        submitForm('create');
    });

    $('#editCategoryForm').on('submit', function(e) {
        e.preventDefault();
        submitForm('edit');
    });

    // Reset form when modals close
    $('#createCategoryModal').on('hidden.bs.modal', function () {
        resetCreateForm();
    });

    $('#editCategoryModal').on('hidden.bs.modal', function () {
        resetEditForm();
    });

    $('#create_image').on('change', function() {
        handleImagePreview(this);
    });

    $('#create_image_url').on('input', function() {
        handleImageUrlPreview(this);
    });

    $('#edit_image').on('change', function() {
        handleImagePreview(this);
    });

    $('#edit_image_url').on('input', function() {
        handleImageUrlPreview(this);
    });
});

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

    const prevDisabled = pagination.current_page === 1 ? 'disabled' : '';
    container.append(`<li class="page-item ${prevDisabled}"><a class="page-link" href="#" onclick="changePage(${pagination.current_page - 1}); return false;">Précédent</a></li>`);

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
            resetEditForm();
            $('#editCategoryId').val(category.id);
            $('#edit_nomcat').val(category.nomcat);
            $('#edit_description').val(category.description || '');
            $('#edit_status').val(category.status);
            $('#edit_image_url').val(category.image && category.image.match(/^https?:\/\//i) ? category.image : '');
            $('#edit_image').val('');

            if (category.image) {
                const previewUrl = getValidImageUrl(category.image);
                $('#edit_previewImg').attr('src', previewUrl);
                $('#edit_imagePreview').show();
            } else {
                $('#edit_imagePreview').hide();
            }

            const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
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

function submitForm(type) {
    const prefix = type === 'edit' ? 'edit' : 'create';
    const form = $(`#${prefix}CategoryForm`);
    const imageUrlInput = $(`#${prefix}_image_url`);
    const normalizedUrl = normalizeImageUrl(imageUrlInput.val());
    imageUrlInput.val(normalizedUrl);

    const formData = new FormData(form[0]);
    const isEdit = type === 'edit' && $(`#${prefix}CategoryId`).val();

    if (isEdit) {
        formData.append('_method', 'PUT');
    }

    $(`#${prefix}SubmitBtn`).prop('disabled', true);
    $(`#${prefix}SubmitBtn .spinner-border`).show();

    $('.invalid-feedback').text('');
    $('.form-control, .form-select').removeClass('is-invalid');

    $.ajax({
        url: isEdit ? `/admin/category/${$(`#${prefix}CategoryId`).val()}` : '{{ route("admin.category.store") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $(`#${prefix}SubmitBtn`).prop('disabled', false);
            $(`#${prefix}SubmitBtn .spinner-border`).hide();
            bootstrap.Modal.getInstance(document.getElementById(`${prefix}CategoryModal`)).hide();
            showToast(response.message, 'success');
            if (type === 'create') {
                resetCreateForm();
            } else {
                resetEditForm();
            }
            loadCategories();
        },
        error: function(xhr) {
            $(`#${prefix}SubmitBtn`).prop('disabled', false);
            $(`#${prefix}SubmitBtn .spinner-border`).hide();
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    const fieldId = field === 'nomcat' ? `${prefix}_nomcat` : `${prefix}_${field}`;
                    const errorId = field === 'image_url' ? `${prefix}_imageUrlError` : `${fieldId}Error`;
                    $(`#${errorId}`).text(errors[field][0]);
                    $(`#${fieldId}`).addClass('is-invalid');
                });
            } else {
                showToast('Erreur lors de l\'enregistrement', 'error');
            }
        }
    });
}

function handleImagePreview(input) {
    const prefix = getInputPrefix(input.id);
    if (input.files && input.files[0]) {
        $(`#${prefix}_image_url`).val('');
        const reader = new FileReader();
        reader.onload = function(e) {
            $(`#${prefix}_previewImg`).attr('src', e.target.result);
            $(`#${prefix}_imagePreview`).show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function handleImageUrlPreview(input) {
    const prefix = getInputPrefix(input.id);
    const normalizedUrl = normalizeImageUrl(input.value);
    if (normalizedUrl && normalizedUrl.match(/^https?:\/\//i)) {
        $(`#${prefix}_image`).val('');
        $(`#${prefix}_previewImg`).attr('src', normalizedUrl);
        $(`#${prefix}_imagePreview`).show();
        $(`#${prefix}_image_url`).val(normalizedUrl);
    } else if (!input.value) {
        $(`#${prefix}_imagePreview`).hide();
    }
}

function removeImage(prefix) {
    $(`#${prefix}_image`).val('');
    $(`#${prefix}_imagePreview`).hide();
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
