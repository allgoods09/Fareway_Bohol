{{-- resources/views/report-issue.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-flag"></i> Report an Issue</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Help us improve Fareway Bohol by reporting any issues you encounter.</p>
                    
                    <form id="report-form">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="report_type" class="form-label">Issue Type *</label>
                            <select class="form-control" id="report_type" name="type" required>
                                <option value="">Select issue type</option>
                                <option value="wrong_fare">Wrong Fare Calculation</option>
                                <option value="road_closure">Road Closure / Detour</option>
                                <option value="vehicle_unavailable">Vehicle Not Available</option>
                                <option value="technical_issue">Technical Issue with App</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="5" 
                                      placeholder="Please describe the issue in detail..." required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Route Information (Optional)</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="origin_info" class="form-control" placeholder="Origin location">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="dest_info" class="form-control" placeholder="Destination location">
                                </div>
                            </div>
                            <small class="text-muted">If this issue is related to a specific route, please provide the locations.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Screenshot (Optional)</label>
                            <input type="file" id="screenshot" class="form-control" accept="image/*">
                            <small class="text-muted">You can upload a screenshot to help us understand the issue better.</small>
                        </div>
                        
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-paper-plane"></i> Submit Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('report-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('type', document.getElementById('report_type').value);
    formData.append('description', document.getElementById('description').value);
    formData.append('origin_info', document.getElementById('origin_info').value);
    formData.append('dest_info', document.getElementById('dest_info').value);
    
    const screenshot = document.getElementById('screenshot').files[0];
    if (screenshot) {
        formData.append('screenshot', screenshot);
    }
    
    fetch('{{ route("user.submit-report") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Report submitted successfully! Thank you for helping us improve.');
            document.getElementById('report-form').reset();
        } else {
            alert('Error submitting report. Please try again.');
        }
    })
    .catch(error => {
        alert('Error submitting report. Please try again.');
    });
});
</script>
@endpush
@endsection