@extends('layouts.admin')

@section('title', 'Inventory - InApp Inventory Dashboard')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="fs-3 mb-1">Inventory</h1>
        <p class="mb-0">Manage your product inventory</p>
      </div>
      <div>
        <a href="create-product.html" class="btn btn-primary">Add Product</a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="d-flex gap-2 mb-3 flex-wrap justify-content-between">
      <input type="text" class="form-control" placeholder="Search products..." style="max-width: 250px;">
      <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary"><i class="ti ti-filter"></i> Filter</button>
        <button class="btn btn-outline-secondary"><i class="ti ti-file-excel"></i> Excel</button>
        <button class="btn btn-outline-secondary"><i class="ti ti-file-pdf"></i> PDF</button>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card table-responsive">
      <table class="table mb-0 text-nowrap table-hover">
        <thead class="table-light border-light">
          <tr>
            <th>Image</th>
            <th>Code</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Price</th>
            <th>Unit</th>
            <th>Quantity</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr class="align-middle">
            <td>
              <a href="product-detail.html">
                <img src="{{ asset('images/product-1.png') }}" alt="" class="avatar avatar-md rounded" />
                <span class="ms-3">Gaming Joy Stick</span>
              </a>
            </td>
            <td>PRD001</td>
            <td>Electronics</td>
            <td>Brand Name</td>
            <td>$99.99</td>
            <td>pcs</td>
            <td>150</td>
            <td>
              <a href="edit-product.html" class=""><i class="ti ti-edit"></i></a>
              <a href="#" class="link-danger ms-2"><i class="ti ti-trash"></i></a>
            </td>
          </tr>
          <tr class="align-middle">
            <td>
              <a href="product-detail.html">
                <img src="{{ asset('images/product-2.png') }}" alt="" class="avatar avatar-md rounded" />
                <span class="ms-3">Wireless Earphones</span>
              </a>
            </td>
            <td>PRD002</td>
            <td>Electronics</td>
            <td>Tech Pro</td>
            <td>$89.99</td>
            <td>pcs</td>
            <td>320</td>
            <td>
              <a href="edit-product.html" class=""><i class="ti ti-edit"></i></a>
              <a href="#" class="link-danger ms-2"><i class="ti ti-trash"></i></a>
            </td>
          </tr>
          <tr class="align-middle">
            <td>
              <a href="product-detail.html">
                <img src="{{ asset('images/product-3.png') }}" alt="" class="avatar avatar-md rounded" />
                <span class="ms-3">Smart Watch Pro</span>
              </a>
            </td>
            <td>PRD003</td>
            <td>Electronics</td>
            <td>Tech Pro</td>
            <td>$98.00</td>
            <td>pcs</td>
            <td>200</td>
            <td>
              <a href="edit-product.html" class=""><i class="ti ti-edit"></i></a>
              <a href="#" class="link-danger ms-2"><i class="ti ti-trash"></i></a>
            </td>
          </tr>
          <tr class="align-middle">
            <td>
              <a href="product-detail.html">
                <img src="{{ asset('images/product-4.png') }}" alt="" class="avatar avatar-md rounded" />
                <span class="ms-3">USB-C Fast Charger</span>
              </a>
            </td>
            <td>PRD004</td>
            <td>Electronics</td>
            <td>Tech Pro</td>
            <td>$86.00</td>
            <td>pcs</td>
            <td>80</td>
            <td>
              <a href="edit-product.html" class=""><i class="ti ti-edit"></i></a>
              <a href="#" class="link-danger ms-2"><i class="ti ti-trash"></i></a>
            </td>
          </tr>
          <tr class="align-middle">
            <td>
              <a href="product-detail.html">
                <img src="{{ asset('images/product-5.png') }}" alt="" class="avatar avatar-md rounded" />
                <span class="ms-3">Portable Bluetooth Speaker</span>
              </a>
            </td>
            <td>PRD005</td>
            <td>Electronics</td>
            <td>Tech Pro</td>
            <td>$32.00</td>
            <td>pcs</td>
            <td>110</td>
            <td>
              <a href="edit-product.html" class=""><i class="ti ti-edit"></i></a>
              <a href="#" class="link-danger ms-2"><i class="ti ti-trash"></i></a>
            </td>
          </tr>
          <tr class="align-middle">
            <td>
              <a href="product-detail.html">
                <img src="{{ asset('images/product-6.png') }}" alt="" class="avatar avatar-md rounded" />
                <span class="ms-3">Magic Keyboard</span>
              </a>
            </td>
            <td>PRD006</td>
            <td>Electronics</td>
            <td>Tech Pro</td>
            <td>$49.00</td>
            <td>pcs</td>
            <td>10</td>
            <td>
              <a href="edit-product.html" class=""><i class="ti ti-edit"></i></a>
              <a href="#" class="link-danger ms-2"><i class="ti ti-trash"></i></a>
            </td>
          </tr>
          <tr class="align-middle">
            <td>
              <a href="product-detail.html">
                <img src="{{ asset('images/product-7.png') }}" alt="" class="avatar avatar-md rounded" />
                <span class="ms-3">MacBook Pro 16"</span>
              </a>
            </td>
            <td>PRD007</td>
            <td>Electronics</td>
            <td>Tech Pro</td>
            <td>$99.00</td>
            <td>pcs</td>
            <td>10</td>
            <td>
              <a href="edit-product.html" class=""><i class="ti ti-edit"></i></a>
              <a href="#" class="link-danger ms-2"><i class="ti ti-trash"></i></a>
            </td>
          </tr>
          <tr class="align-middle">
            <td>
              <a href="product-detail.html">
                <img src="{{ asset('images/product-8.png') }}" alt="" class="avatar avatar-md rounded" />
                <span class="ms-3">Wireless Headphones</span>
              </a>
            </td>
            <td>PRD008</td>
            <td>Electronics</td>
            <td>Tech Pro</td>
            <td>$109.00</td>
            <td>pcs</td>
            <td>200</td>
            <td>
              <a href="edit-product.html" class=""><i class="ti ti-edit"></i></a>
              <a href="#" class="link-danger ms-2"><i class="ti ti-trash"></i></a>
            </td>
          </tr>
          <tr class="align-middle">
            <td>
              <a href="product-detail.html">
                <img src="{{ asset('images/product-9.png') }}" alt="" class="avatar avatar-md rounded" />
                <span class="ms-3">AirPods Pro Max</span>
              </a>
            </td>
            <td>PRD009</td>
            <td>Electronics</td>
            <td>Tech Pro</td>
            <td>$549.00</td>
            <td>pcs</td>
            <td>45</td>
            <td>
              <a href="edit-product.html" class=""><i class="ti ti-edit"></i></a>
              <a href="#" class="link-danger ms-2"><i class="ti ti-trash"></i></a>
            </td>
          </tr>
          <tr class="align-middle">
            <td>
              <a href="product-detail.html">
                <img src="{{ asset('images/product-10.png') }}" alt="" class="avatar avatar-md rounded" />
                <span class="ms-3">Phone Screen Protector</span>
              </a>
            </td>
            <td>PRD010</td>
            <td>Electronics</td>
            <td>Tech Pro</td>
            <td>$15.00</td>
            <td>pcs</td>
            <td>500</td>
            <td>
              <a href="edit-product.html" class=""><i class="ti ti-edit"></i></a>
              <a href="#" class="link-danger ms-2"><i class="ti ti-trash"></i></a>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td class="border-bottom-0">Showing 10 products</td>
            <td colspan="7" class="border-bottom-0">
              <nav aria-label="Page navigation" class="d-flex justify-content-end">
                <ul class="pagination mb-0">
                  <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                  <li class="page-item active"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
              </nav>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection