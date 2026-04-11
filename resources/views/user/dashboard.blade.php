@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center p-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="fas fa-user-circle fa-4x text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>
                    <hr class="my-3">
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-user-edit me-2"></i>Edit Profile
                    </a>
                </div>
            </div>
            
            <div class="list-group mt-3 rounded-3 overflow-hidden">
                <a href="{{ route('user.dashboard') }}" class="list-group-item list-group-item-action active bg-primary border-primary">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
                <a href="{{ route('user.saved-routes') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-bookmark me-2"></i> Saved Routes
                </a>
                <a href="{{ route('user.report-issue') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-flag me-2"></i> Report Issue
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-8 col-lg-9">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold text-primary mb-0">
                        <i class="fas fa-chart-line me-2"></i>Your Activity
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Stats Row -->
                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <i class="fas fa-bookmark fa-2x text-primary mb-2"></i>
                                <h3 class="fw-bold mb-0">{{ $savedRoutes->total() }}</h3>
                                <p class="text-muted small mb-0">Saved Routes</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <i class="fas fa-flag fa-2x text-success mb-2"></i>
                                <h3 class="fw-bold mb-0">{{ $reportsCount ?? 0 }}</h3>
                                <p class="text-muted small mb-0">Reports Submitted</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="bg-light rounded-3 p-3 text-center">
                                <i class="fas fa-route fa-2x text-info mb-2"></i>
                                <h3 class="fw-bold mb-0">{{ $searchesCount ?? 0 }}</h3>
                                <p class="text-muted small mb-0">Route Searches</p>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-clock me-2"></i>Recent Saved Routes
                    </h6>
                    
                    @forelse($savedRoutes as $route)
                    <div class="border rounded-3 p-3 mb-3 hover-shadow">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $route->name }}</h6>
                                <small class="text-muted d-block">
                                    <i class="fas fa-map-marker-alt text-success me-1"></i>
                                    From: {{ Str::limit($route->origin_address ?? $route->origin_lat . ', ' . $route->origin_lng, 50) }}
                                </small>
                                <small class="text-muted d-block">
                                    <i class="fas fa-flag-checkered text-danger me-1"></i>
                                    To: {{ Str::limit($route->dest_address ?? $route->dest_lat . ', ' . $route->dest_lng, 50) }}
                                </small>
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    Saved {{ $route->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('find-route') }}?origin={{ $route->origin_lat }},{{ $route->origin_lng }}&dest={{ $route->dest_lat }},{{ $route->dest_lng }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-route me-1"></i>View
                                </a>
                                <button class="btn btn-danger btn-sm delete-route" data-id="{{ $route->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 bg-light rounded-3">
                        <i class="fas fa-bookmark fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-2">No saved routes yet.</p>
                        <a href="{{ route('find-route') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-route me-1"></i>Find a Route
                        </a>
                    </div>
                    @endforelse
                    
                    <div class="mt-3">
                        {{ $savedRoutes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: box-shadow 0.2s ease;
}
.hover-shadow:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>

@push('scripts')
<script>
document.querySelectorAll('.delete-route').forEach(btn => {
    btn.addEventListener('click', function() {
        if(confirm('Delete this saved route?')) {
            const id = this.dataset.id;
            fetch(`/user/saved-routes/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                location.reload();
            });
        }
    });
});
</script>
@endpush
@endsection