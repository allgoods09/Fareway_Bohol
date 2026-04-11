{{-- resources/views/user/report.blade.php --}}
@extends('layouts.user')

@section('title', 'Report an Issue')

@section('hero-content')
<div class="hero-content">
    <div class="hero-tag">
        <div class="hero-tag-dot"></div>
        Report Issue
    </div>
    <h1>Report <span>an Issue</span></h1>
    <p>Help us improve Fareway Bohol by reporting any problems you encounter</p>
</div>
@endsection

@section('content')
<div class="report-container">
    <div class="report-card">
        <form action="{{ route('user.report.store') }}" method="POST" enctype="multipart/form-data" class="report-form">
            @csrf

            <div class="form-group">
                <label for="type" class="form-label">
                    <i class="fas fa-tag"></i> Issue Type *
                </label>
                <select name="type" id="type" class="form-control" required>
                    <option value="">Select issue type</option>
                    <option value="wrong_fare">Wrong Fare Calculation</option>
                    <option value="road_closure">Road Closure / Detour</option>
                    <option value="vehicle_unavailable">Vehicle Not Available</option>
                    <option value="technical_issue">Technical Issue with App</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left"></i> Description *
                </label>
                <textarea name="description" id="description" rows="6" class="form-control" 
                          placeholder="Please describe the issue in detail..." required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="origin_info" class="form-label">
                        <i class="fas fa-map-marker-alt"></i> Origin (Optional)
                    </label>
                    <input type="text" name="origin_info" id="origin_info" class="form-control" 
                           placeholder="Where did this happen?">
                </div>
                <div class="form-group">
                    <label for="dest_info" class="form-label">
                        <i class="fas fa-flag-checkered"></i> Destination (Optional)
                    </label>
                    <input type="text" name="dest_info" id="dest_info" class="form-control" 
                           placeholder="Where were you going?">
                </div>
            </div>

            <div class="form-group">
                <label for="screenshot" class="form-label">
                    <i class="fas fa-image"></i> Screenshot (Optional)
                </label>
                <input type="file" name="screenshot" id="screenshot" class="form-control" accept="image/*">
                <p class="form-hint">Upload a screenshot if it helps explain the issue (max 5MB)</p>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Submit Report
                </button>
                <a href="{{ route('home') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .report-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .report-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .report-form {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-mid);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label i {
        color: var(--teal);
        font-size: 12px;
    }

    .form-control {
        padding: 12px 16px;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.2s;
        width: 100%;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--teal);
        box-shadow: 0 0 0 3px rgba(14,138,110,0.1);
    }

    select.form-control {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
    }

    textarea.form-control {
        resize: vertical;
    }

    .form-hint {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .form-actions {
        display: flex;
        gap: 16px;
        margin-top: 8px;
    }

    .btn-submit {
        flex: 1;
        padding: 12px 24px;
        background: var(--teal);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        background: #0c7a60;
        transform: translateY(-1px);
    }

    .btn-cancel {
        flex: 1;
        padding: 12px 24px;
        background: var(--sand);
        color: var(--text-mid);
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: #e5e8eb;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .report-card {
            padding: 24px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endsection