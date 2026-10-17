@if (session('success'))
    <div class="alert alert-success mt-3" role="status">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger mt-3" role="alert">{{ session('error') }}</div>
@endif
