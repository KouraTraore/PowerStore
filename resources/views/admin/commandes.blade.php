@extends('layouts.admin')

@section('title', '404 Error - InApp Inventory Dashboard')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="d-flex align-items-center justify-content-center" style="min-height: 70vh;">
      <div class="text-center" style="max-width: 500px; width: 100%;">
        <h1 class="display-1 fw-bold text-primary mb-2">404</h1>
        <h2 class="h4 mb-3">Page Not Found</h2>
        <p class="text-muted mb-4">Sorry, the page you're looking for doesn't exist or has been moved.</p>
        <a href="{{ route('admin.index') }}" class="btn btn-primary">Go to Dashboard</a>
      </div>
    </div>
  </div>
</div>
@endsection