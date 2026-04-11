{{-- resources/views/about.blade.php --}}
@extends('layouts.user')

@section('title', 'About Fareway Bohol')

@section('hero-content')
<div class="hero-content">
    <div class="hero-tag">
        <div class="hero-tag-dot"></div>
        About Us
    </div>
    <h1>About <span>Fareway Bohol</span></h1>
    <p>Making public transport in Bohol accessible and transparent for everyone</p>
</div>
@endsection

@section('content')
<div class="about-container">
    <!-- Mission Section -->
    <div class="about-section">
        <h2>Our Mission</h2>
        <p>To provide accurate, real-time fare estimates and route information for public transport users in Bohol, making commuting easier and more transparent for everyone.</p>
    </div>

    <!-- What We Offer -->
    <div class="about-section">
        <h2>What We Offer</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-route"></i>
                <h3>Route Finder</h3>
                <p>Find the best routes between any two points in Bohol with accurate distance and time estimates.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-money-bill-wave"></i>
                <h3>Fare Calculator</h3>
                <p>Get instant fare estimates for all vehicle types including tricycles, motorcycles, multi-cabs, and buses.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-map-marked-alt"></i>
                <h3>Tourist Spots</h3>
                <p>Discover popular destinations across Bohol with one-click routing.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-save"></i>
                <h3>Save Routes</h3>
                <p>Bookmark your favorite routes for quick access later.</p>
            </div>
        </div>
    </div>

    <!-- Vehicle Types -->
    <div class="about-section">
        <h2>Vehicle Types</h2>
        <div class="vehicles-grid">
            <div class="vehicle-card">
                <i class="fas fa-motorcycle"></i>
                <h3>Tricycle</h3>
                <p>Perfect for short distances and barangay roads</p>
            </div>
            <div class="vehicle-card">
                <i class="fas fa-motorcycle"></i>
                <h3>Motorcycle</h3>
                <p>Fast and convenient for solo travelers</p>
            </div>
            <div class="vehicle-card">
                <i class="fas fa-van-shuttle"></i>
                <h3>Multi-cab</h3>
                <p>Ideal for group travel and medium distances</p>
            </div>
            <div class="vehicle-card">
                <i class="fas fa-bus"></i>
                <h3>Bus</h3>
                <p>Best for long-distance travel across municipalities</p>
            </div>
        </div>
    </div>
</div>

<style>
    .about-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .about-section {
        margin-bottom: 56px;
    }

    .about-section h2 {
        font-size: 24px;
        font-weight: 600;
        color: var(--navy);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--teal);
        display: inline-block;
    }

    .about-section p {
        font-size: 15px;
        line-height: 1.7;
        color: var(--text-mid);
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-top: 24px;
    }

    .feature-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 28px 20px;
        text-align: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }

    .feature-card i {
        font-size: 40px;
        color: var(--teal);
        margin-bottom: 16px;
    }

    .feature-card h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 10px;
    }

    .feature-card p {
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.5;
        margin: 0;
    }

    .vehicles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-top: 24px;
    }

    .vehicle-card {
        background: var(--sand);
        border-radius: 16px;
        padding: 24px 20px;
        text-align: center;
        transition: transform 0.2s;
    }

    .vehicle-card:hover {
        transform: translateY(-2px);
    }

    .vehicle-card i {
        font-size: 36px;
        color: var(--navy);
        margin-bottom: 12px;
    }

    .vehicle-card h3 {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .vehicle-card p {
        font-size: 12px;
        color: var(--text-muted);
        margin: 0;
    }

    @media (max-width: 768px) {
        .features-grid, .vehicles-grid {
            grid-template-columns: 1fr;
        }
        
        .about-section h2 {
            font-size: 20px;
        }
    }
</style>
@endsection