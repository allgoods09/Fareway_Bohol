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
    <!-- Hero Stats Banner -->
    <div class="about-stats-banner">
        <div class="stat-item">
            <div class="stat-number">12+</div>
            <div class="stat-label">Municipalities Covered</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $vehicleCount ?? 0}}</div>
            <div class="stat-label">Vehicle Types</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $totalRoutesCalculated ?? 0}}</div>
            <div class="stat-label">Routes Calculated</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Available</div>
        </div>
    </div>

    <!-- Mission & Vision Row -->
    <div class="mission-vision-row">
        <div class="mission-card">
            <div class="card-icon"><i class="fas fa-bullseye"></i></div>
            <h3>Our Mission</h3>
            <p>To provide accurate, real-time fare estimates and route information for public transport users in Bohol, making commuting easier and more transparent for everyone.</p>
        </div>
        <div class="vision-card">
            <div class="card-icon"><i class="fas fa-eye"></i></div>
            <h3>Our Vision</h3>
            <p>A Bohol where every commuter and tourist can navigate the province with confidence, transparency, and ease using reliable transport information.</p>
        </div>
    </div>

    <!-- Story Section with Image -->
    <div class="story-section">
        <div class="story-image">
            <img src="https://images.unsplash.com/photo-1728042743743-e2a2abf35c47?q=80&w=1075&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                 alt="Chocolate Hills Bohol"
                 onerror="this.src='https://placehold.co/600x400/0c2340/34d399?text=Bohol'">
        </div>
        <div class="story-content">
            <h2>Our Story</h2>
            <p>Fareway Bohol was born from a simple observation: commuters and tourists in Bohol struggle to know accurate fare rates and route options. Traditional jeepney signs are confusing, tricycle fares are often negotiated arbitrarily, and tourists have no idea how much a trip should cost.</p>
            <p>We built Fareway Bohol to solve this problem. By providing transparent fare estimates and clear route information, we empower both locals and visitors to travel with confidence.</p>
            <p>Today, Fareway Bohol is the go-to platform for public transport information across the province, trusted by thousands of users daily.</p>
        </div>
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

    <!-- Vehicle Types with Images -->
    <div class="about-section">
        <h2>Vehicle Types</h2>
        <div class="vehicles-showcase">
            <div class="vehicle-card">
                <div class="vehicle-icon"><i class="fas fa-motorcycle"></i></div>
                <h3>Tricycle</h3>
                <p>Perfect for short distances and barangay roads. Ideal for 2-3 passengers.</p>
                <div class="vehicle-badge">Best for: Short trips</div>
            </div>
            <div class="vehicle-card">
                <div class="vehicle-icon"><i class="fas fa-motorcycle"></i></div>
                <h3>Motorcycle</h3>
                <p>Fast and convenient for solo travelers or couples. Quickest option for busy routes.</p>
                <div class="vehicle-badge">Best for: Solo travel</div>
            </div>
            <div class="vehicle-card">
                <div class="vehicle-icon"><i class="fas fa-van-shuttle"></i></div>
                <h3>Multi-cab</h3>
                <p>Ideal for group travel and medium distances. Comfortable for 6-8 passengers.</p>
                <div class="vehicle-badge">Best for: Groups</div>
            </div>
            <div class="vehicle-card">
                <div class="vehicle-icon"><i class="fas fa-bus"></i></div>
                <h3>Bus</h3>
                <p>Best for long-distance travel across municipalities. Most economical for far trips.</p>
                <div class="vehicle-badge">Best for: Long distance</div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="why-us-section">
        <h2>Why Choose Fareway Bohol</h2>
        <div class="why-grid">
            <div class="why-item">
                <i class="fas fa-check-circle"></i>
                <div>
                    <h4>100% Free</h4>
                    <p>No hidden fees, no premium plans — completely free for everyone.</p>
                </div>
            </div>
            <div class="why-item">
                <i class="fas fa-clock"></i>
                <div>
                    <h4>Real-time Updates</h4>
                    <p>Fare rates updated based on LTFRB guidelines and fuel price changes.</p>
                </div>
            </div>
            <div class="why-item">
                <i class="fas fa-mobile-alt"></i>
                <div>
                    <h4>Mobile Friendly</h4>
                    <p>Fully responsive design works on any device — phone, tablet, or desktop.</p>
                </div>
            </div>
            <div class="why-item">
                <i class="fas fa-headset"></i>
                <div>
                    <h4>Community Driven</h4>
                    <p>Report issues and help us improve the platform for everyone.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact & Support -->
    <div class="contact-section">
        <div class="contact-info">
            <h3><i class="fas fa-headset"></i> Need Help?</h3>
            <p>Have questions or found an incorrect fare? Reach out to our support team.</p>
            <div class="contact-details">
                <a href="#" onclick="showToast('📧 Email: support@farewaybohol.com', 'info')"><i class="fas fa-envelope"></i> support@farewaybohol.com</a>
                <a href="#" onclick="showToast('📞 Hotline: (038) 123-4567', 'info')"><i class="fas fa-phone"></i> (038) 123-4567</a>
                <a href="#" onclick="showToast('📍 Capitol Compound, Tagbilaran City', 'info')"><i class="fas fa-map-marker-alt"></i> Tagbilaran City, Bohol</a>
            </div>
        </div>
        <div class="report-issue">
            <i class="fas fa-flag"></i>
            <div>
                <h4>See something wrong?</h4>
                <p>Report incorrect fares, road closures, or technical issues</p>
                <a href="{{ route('user.report.create') }}" class="report-btn">Report an Issue →</a>
            </div>
        </div>
    </div>
</div>

<style>
    .about-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Stats Banner */
    .about-stats-banner {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
        border-radius: 16px;
        padding: 32px 24px;
        margin-bottom: 48px;
        text-align: center;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: #34d399;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 13px;
        color: rgba(255,255,255,0.7);
    }

    /* Mission Vision Row */
    .mission-vision-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 48px;
    }

    .mission-card, .vision-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 28px;
        text-align: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .mission-card:hover, .vision-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .card-icon {
        width: 60px;
        height: 60px;
        background: var(--teal-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .card-icon i {
        font-size: 28px;
        color: var(--teal);
    }

    .mission-card h3, .vision-card h3 {
        font-size: 20px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 12px;
    }

    .mission-card p, .vision-card p {
        font-size: 14px;
        line-height: 1.6;
        color: var(--text-mid);
    }

    /* Story Section */
    .story-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 56px;
        background: var(--sand);
        border-radius: 16px;
        overflow: hidden;
    }

    .story-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .story-content {
        padding: 40px 40px 40px 0;
    }

    .story-content h2 {
        font-size: 24px;
        font-weight: 600;
        color: var(--navy);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--teal);
        display: inline-block;
    }

    .story-content p {
        font-size: 14px;
        line-height: 1.7;
        color: var(--text-mid);
        margin-bottom: 16px;
    }

    /* What We Offer */
    .about-section {
        margin-bottom: 56px;
    }

    .about-section h2 {
        font-size: 24px;
        font-weight: 600;
        color: var(--navy);
        margin-bottom: 24px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--teal);
        display: inline-block;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-top: 0;
    }

    .feature-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 28px 20px;
        text-align: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .feature-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .feature-card i {
        font-size: 40px;
        color: var(--teal);
        margin-bottom: 16px;
    }

    .feature-card h3 {
        font-size: 16px;
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

    /* Vehicle Showcase */
    .vehicles-showcase {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
    }

    .vehicle-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px 20px;
        text-align: center;
        transition: transform 0.2s;
    }

    .vehicle-card:hover {
        transform: translateY(-2px);
    }

    .vehicle-icon {
        width: 64px;
        height: 64px;
        background: var(--teal-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .vehicle-icon i {
        font-size: 32px;
        color: var(--teal);
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
        margin-bottom: 12px;
    }

    .vehicle-badge {
        display: inline-block;
        background: var(--sand);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
        color: var(--teal);
    }

    /* Why Choose Us */
    .why-us-section {
        background: var(--sand);
        border-radius: 16px;
        padding: 40px;
        margin-bottom: 48px;
    }

    .why-us-section h2 {
        font-size: 24px;
        font-weight: 600;
        color: var(--navy);
        margin-bottom: 28px;
        text-align: center;
    }

    .why-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
    }

    .why-item {
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }

    .why-item i {
        font-size: 28px;
        color: var(--teal);
        flex-shrink: 0;
    }

    .why-item h4 {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .why-item p {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.5;
    }

    /* Contact Section */
    .contact-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
        border-radius: 16px;
        padding: 40px;
        color: white;
    }

    .contact-info h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .contact-info p {
        font-size: 13px;
        opacity: 0.8;
        margin-bottom: 20px;
    }

    .contact-details {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .contact-details a {
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: color 0.2s;
    }

    .contact-details a:hover {
        color: #34d399;
    }

    .report-issue {
        background: rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 24px;
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }

    .report-issue i {
        font-size: 36px;
        opacity: 0.8;
    }

    .report-issue h4 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .report-issue p {
        font-size: 12px;
        opacity: 0.7;
        margin-bottom: 12px;
    }

    .report-btn {
        display: inline-block;
        background: var(--teal);
        color: white;
        padding: 8px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .report-btn:hover {
        background: #0c7a60;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .about-stats-banner {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .mission-vision-row {
            grid-template-columns: 1fr;
        }

        .story-section {
            grid-template-columns: 1fr;
        }

        .story-content {
            padding: 24px;
        }

        .contact-section {
            grid-template-columns: 1fr;
        }

        .why-grid {
            grid-template-columns: 1fr;
        }

        .stat-number {
            font-size: 24px;
        }
    }

    @media (max-width: 480px) {
        .about-stats-banner {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection