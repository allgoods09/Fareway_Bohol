@extends('layouts.user')

@section('title', 'Latest News')

@section('hero-content')
<div class="hero-content">
    <div class="hero-tag">
        <div class="hero-tag-dot"></div>
        Breaking News
    </div>
    <h1><span>Latest</span> Headlines</h1>
    <p>Transport updates, fuel prices, and tourism news across the Philippines</p>
</div>
@endsection

@section('content')
<div class="news-container">
    <!-- Top Bar -->
    <div class="news-top-bar">
        <div class="live-indicator">
            <span class="live-dot"></span> LIVE UPDATES
        </div>
        <div class="news-meta-bar">
            <span><i class="far fa-clock"></i> Last updated: <span id="last-updated">{{ $lastUpdated->format('F j, Y g:i A') }}</span></span>
            <span class="api-note">📡 Free API • 12-hour delay</span>
            <button id="refresh-news-btn" class="refresh-link"><i class="fas fa-sync-alt"></i> Refresh</button>
        </div>
    </div>

    <!-- Loading State -->
    <div id="news-loading" class="news-loading">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading latest news...</p>
        </div>
    </div>

    <!-- News Content (hidden initially) -->
    <div id="news-content" style="display: none;"></div>
</div>

<style>
    .news-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Top Bar - CNN Style */
    .news-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding-bottom: 12px;
        margin-bottom: 24px;
        border-bottom: 2px solid #cc0000;
    }

    .live-indicator {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #cc0000;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .live-dot {
        width: 8px;
        height: 8px;
        background-color: #cc0000;
        border-radius: 50%;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
        100% { opacity: 1; transform: scale(1); }
    }

    .news-meta-bar {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 11px;
        color: #666;
    }

    .refresh-link {
        background: none;
        border: none;
        color: #333;
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
        font-family: inherit;
    }

    .refresh-link:hover {
        color: #cc0000;
    }

    .api-note {
        background: #f0f0f0;
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 10px;
    }

    /* Loading State */
    .news-loading {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 400px;
    }

    .loading-spinner {
        text-align: center;
    }

    .loading-spinner i {
        font-size: 48px;
        color: #cc0000;
        margin-bottom: 16px;
    }

    .loading-spinner p {
        font-size: 14px;
        color: #666;
    }

    /* Featured Story - Large Hero */
    .featured-story {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 1px solid #ddd;
    }

    @media (max-width: 768px) {
        .featured-story {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    .featured-image {
        position: relative;
        background: #f5f5f5;
        border-radius: 0;
        overflow: hidden;
    }

    .featured-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        aspect-ratio: 16/9;
    }

    .featured-image-placeholder {
        aspect-ratio: 16/9;
        background: linear-gradient(135deg, #1a1a2e, #16213e);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .featured-image-placeholder i {
        font-size: 48px;
        color: #fff;
        opacity: 0.5;
    }

    .featured-category {
        position: absolute;
        bottom: 12px;
        left: 12px;
        background: #cc0000;
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 10px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .featured-title {
        font-size: 28px;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 16px;
    }

    .featured-title a {
        color: #1a1a1a;
        text-decoration: none;
    }

    .featured-title a:hover {
        text-decoration: underline;
    }

    .featured-description {
        font-size: 16px;
        line-height: 1.5;
        color: #444;
        margin-bottom: 20px;
    }

    .featured-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #eee;
        padding-top: 16px;
    }

    .featured-date {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
    }

    .read-more-link {
        font-size: 13px;
        font-weight: 600;
        color: #cc0000;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .read-more-link:hover {
        text-decoration: underline;
    }

    /* Section Divider */
    .section-divider {
        text-align: center;
        margin: 30px 0 25px;
        position: relative;
    }

    .section-divider span {
        background: white;
        padding: 0 16px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2px;
        color: #666;
        position: relative;
        z-index: 1;
    }

    .section-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #ddd;
        z-index: 0;
    }

    /* Secondary Grid - 2 columns */
    .secondary-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        margin-bottom: 40px;
    }

    @media (max-width: 768px) {
        .secondary-grid {
            grid-template-columns: 1fr;
        }
    }

    .secondary-item {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .secondary-image {
        overflow: hidden;
    }

    .secondary-image img {
        width: 100%;
        aspect-ratio: 16/9;
        object-fit: cover;
    }

    .secondary-source {
        font-size: 10px;
        font-weight: 700;
        color: #cc0000;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .secondary-title {
        font-size: 18px;
        font-weight: 700;
        line-height: 1.3;
        margin: 0;
    }

    .secondary-title a {
        color: #1a1a1a;
        text-decoration: none;
    }

    .secondary-title a:hover {
        text-decoration: underline;
    }

    .secondary-description {
        font-size: 13px;
        color: #555;
        line-height: 1.5;
        margin: 0;
    }

    .secondary-date {
        font-size: 11px;
        color: #999;
        margin-top: 8px;
    }

    /* News List - CNN style */
    .news-list {
        display: flex;
        flex-direction: column;
    }

    .list-item {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 20px 0;
        border-bottom: 1px solid #eee;
    }

    .list-item:last-child {
        border-bottom: none;
    }

    .list-content {
        flex: 3;
    }

    .list-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
        font-size: 11px;
    }

    .list-source {
        font-weight: 700;
        color: #cc0000;
        text-transform: uppercase;
    }

    .list-bullet {
        color: #ccc;
    }

    .list-date {
        color: #999;
    }

    .list-title {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 8px 0;
        line-height: 1.3;
    }

    .list-title a {
        color: #1a1a1a;
        text-decoration: none;
    }

    .list-title a:hover {
        text-decoration: underline;
    }

    .list-description {
        font-size: 13px;
        color: #555;
        line-height: 1.5;
        margin: 0;
    }

    .list-image {
        flex: 1;
        min-width: 100px;
    }

    .list-image img {
        width: 100%;
        aspect-ratio: 16/9;
        object-fit: cover;
    }

    @media (max-width: 600px) {
        .list-item {
            flex-direction: column;
        }
        
        .list-image {
            order: -1;
        }
    }

    /* No news */
    .no-news {
        text-align: center;
        padding: 60px 20px;
        background: #f9f9f9;
        border: 1px solid #eee;
    }

    .no-news i {
        font-size: 48px;
        color: #ccc;
        margin-bottom: 16px;
    }

    .no-news h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
    }
</style>

@push('scripts')
<script>
    // Load news via AJAX
    async function loadNews() {
        const loadingDiv = document.getElementById('news-loading');
        const contentDiv = document.getElementById('news-content');
        const lastUpdatedSpan = document.getElementById('last-updated');
        
        try {
            const response = await fetch('{{ route("user.news.fetch") }}');
            const data = await response.json();
            
            if (data.success && data.articles.length > 0) {
                // Update last updated time
                lastUpdatedSpan.textContent = data.lastUpdated;
                
                // Render news
                contentDiv.innerHTML = renderNews(data.articles);
                contentDiv.style.display = 'block';
                loadingDiv.style.display = 'none';
            } else {
                contentDiv.innerHTML = `
                    <div class="no-news">
                        <i class="fas fa-newspaper"></i>
                        <h3>No news available</h3>
                        <p>Unable to fetch news at this time. Please try again later.</p>
                        <button onclick="loadNews()" class="refresh-btn">Try Again</button>
                    </div>
                `;
                contentDiv.style.display = 'block';
                loadingDiv.style.display = 'none';
            }
        } catch (error) {
            console.error('Error loading news:', error);
            contentDiv.innerHTML = `
                <div class="no-news">
                    <i class="fas fa-exclamation-circle"></i>
                    <h3>Connection Error</h3>
                    <p>Unable to load news. Please check your internet connection.</p>
                    <button onclick="loadNews()" class="refresh-btn">Try Again</button>
                </div>
            `;
            contentDiv.style.display = 'block';
            loadingDiv.style.display = 'none';
        }
    }
    
    function renderNews(articles) {
        const featured = articles[0];
        const secondary = articles.slice(1, 6);
        const rest = articles.slice(6);
        
        let html = '';
        
        // Featured Story
        if (featured) {
            html += `
                <div class="featured-story">
                    <div class="featured-image">
                        ${featured.image && featured.image !== 'null' ? 
                            `<img src="${featured.image}" alt="${featured.title.replace(/"/g, '&quot;')}" onerror="this.src='https://placehold.co/800x450/e2e8f0/64748b?text=News'">` :
                            `<div class="featured-image-placeholder"><i class="fas fa-newspaper"></i></div>`
                        }
                        <div class="featured-category">${featured.source?.name || 'Latest'}</div>
                    </div>
                    <div class="featured-content">
                        <h2 class="featured-title">
                            <a href="${featured.url}" target="_blank" rel="noopener">${featured.title}</a>
                        </h2>
                        <p class="featured-description">${featured.description || 'Click to read full article...'}</p>
                        <div class="featured-footer">
                            <span class="featured-date">${new Date(featured.publishedAt).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
                            <a href="${featured.url}" class="read-more-link" target="_blank">Read full story <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Section Divider - Top Stories
        if (secondary.length > 0) {
            html += `<div class="section-divider"><span>TOP STORIES</span></div>`;
            html += `<div class="secondary-grid">`;
            
            secondary.forEach(article => {
                html += `
                    <div class="secondary-item">
                        ${article.image && article.image !== 'null' ? 
                            `<div class="secondary-image"><img src="${article.image}" alt="${article.title.replace(/"/g, '&quot;')}" onerror="this.style.display='none'"></div>` : 
                            ''
                        }
                        <div class="secondary-content">
                            <div class="secondary-source">${article.source?.name || 'News'}</div>
                            <h3 class="secondary-title">
                                <a href="${article.url}" target="_blank" rel="noopener">${article.title}</a>
                            </h3>
                            <p class="secondary-description">${article.description ? article.description.substring(0, 100) + '...' : 'No description available'}</p>
                            <div class="secondary-date">${new Date(article.publishedAt).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</div>
                        </div>
                    </div>
                `;
            });
            
            html += `</div>`;
        }
        
        // Section Divider - More News
        if (rest.length > 0) {
            html += `<div class="section-divider"><span>MORE NEWS</span></div>`;
            html += `<div class="news-list">`;
            
            rest.forEach(article => {
                html += `
                    <div class="list-item">
                        <div class="list-content">
                            <div class="list-meta">
                                <span class="list-source">${article.source?.name || 'News'}</span>
                                <span class="list-bullet">•</span>
                                <span class="list-date">${new Date(article.publishedAt).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                            </div>
                            <h4 class="list-title">
                                <a href="${article.url}" target="_blank" rel="noopener">${article.title}</a>
                            </h4>
                            <p class="list-description">${article.description ? article.description.substring(0, 120) + '...' : 'No description available'}</p>
                        </div>
                        ${article.image && article.image !== 'null' ? 
                            `<div class="list-image"><img src="${article.image}" alt="${article.title.replace(/"/g, '&quot;')}" onerror="this.style.display='none'"></div>` : 
                            ''
                        }
                    </div>
                `;
            });
            
            html += `</div>`;
        }
        
        return html;
    }
    
    // Refresh functionality
    document.getElementById('refresh-news-btn')?.addEventListener('click', async function() {
        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
        btn.disabled = true;
        
        try {
            // Clear cache and reload
            const response = await fetch('{{ route("user.news.refresh") }}');
            if (response.redirected) {
                window.location.href = response.url;
            }
        } catch (error) {
            console.error('Refresh error:', error);
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            showToast('Failed to refresh news', 'error');
        }
    });
    
    // Load news when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadNews();
    });
</script>
@endpush
@endsection