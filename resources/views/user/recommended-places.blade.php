@extends('layouts.user')

@section('title', 'Recommended Places')

@section('hero-content')
<div class="hero-content">
    <div class="hero-tag">
        <div class="hero-tag-dot"></div>
        Discover Bohol
    </div>
    <h1>Explore <span>Tourist Spots</span></h1>
    <p>Discover the beautiful destinations and hidden gems of Bohol, from the iconic Chocolate Hills to pristine beaches.</p>
</div>
@endsection

@section('content')
<div>
    <!-- Search and Filter Bar -->
    <div class="search-filter-bar">
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="search-input" class="search-input" 
                   placeholder="Search for a tourist spot..." autocomplete="off">
            <button id="clear-search" class="clear-search hidden">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="filter-group">
            <select id="category-filter" class="filter-select">
                <option value="all">All Categories</option>
                @php
                    $categories = $places->pluck('category')->unique()->filter();
                @endphp
                @foreach($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
            
            <select id="sort-order" class="filter-select">
                <option value="latest">Latest</option>
                <option value="name_asc">Name (A-Z)</option>
                <option value="name_desc">Name (Z-A)</option>
                <option value="popular">Most Saved</option>
            </select>
        </div>
    </div>

    <!-- Results Info -->
    <div class="results-info">
        <p class="text-gray-600 text-sm" id="results-count">
            Showing <span class="font-semibold text-gray-800">{{ $places->firstItem() ?? 0 }}</span> 
            to <span class="font-semibold text-gray-800">{{ $places->lastItem() ?? 0 }}</span> 
            of <span class="font-semibold text-gray-800">{{ $places->total() }}</span> tourist spots
        </p>
        <p id="search-status" class="search-status hidden"></p>
    </div>

    <!-- Places Grid -->
    <div class="places-grid" id="places-grid">
        @foreach($places as $place)
        <div class="place-card" 
             data-name="{{ $place->name }}" 
             data-saved="{{ $place->saved_routes_count ?? 0 }}"
             data-category="{{ $place->category ?? 'Uncategorized' }}"
             data-description="{{ $place->description }}">
            @if($place->image_url)
                <img src="{{ $place->image_url }}" alt="{{ $place->name }}" class="place-img">
            @else
                <div class="place-img-fallback">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            @endif
            <div class="place-body">
                <div class="place-header">
                    <h3 class="place-name">{{ $place->name }}</h3>
                    @if($place->category)
                        <span class="place-category">{{ $place->category }}</span>
                    @endif
                </div>
                <p class="place-desc">{{ Str::limit($place->description, 100) }}</p>
                <div class="place-stats">
                    <span class="place-stats-item">
                        <i class="fas fa-map-pin"></i> 
                        {{ number_format($place->latitude, 4) }}, {{ number_format($place->longitude, 4) }}
                    </span>
                    <span class="place-stats-item">
                        <i class="fas fa-bookmark"></i> 
                        {{ $place->saved_routes_count ?? 0 }} saves
                    </span>
                </div>
                <div class="place-buttons">
                    <button class="btn-route"
                            onclick="setDestinationFromPlace({{ $place->latitude }}, {{ $place->longitude }}, '{{ addslashes($place->name) }}')">
                        <i class="fas fa-route"></i> Get Route
                    </button>
                    @auth
                        @php
                            $isSaved = auth()->check() && in_array($place->id, $savedPlaceIds ?? []);
                        @endphp
                        <button class="btn-save-place save-place-btn {{ $isSaved ? 'saved' : '' }}" 
                                data-id="{{ $place->id }}"
                                data-name="{{ addslashes($place->name) }}"
                                data-lat="{{ $place->latitude }}"
                                data-lng="{{ $place->longitude }}"
                                {{ $isSaved ? 'disabled' : '' }}>
                            <i class="fas fa-bookmark"></i> 
                            {{ $isSaved ? 'Saved' : 'Save' }}
                        </button>
                    @else
                        <button class="btn-save-place-disabled" onclick="showLoginMessage()">
                            <i class="fas fa-lock"></i> Save
                        </button>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- No Results Message -->
    <div id="no-results" class="no-results hidden">
        <i class="fas fa-map-marked-alt"></i>
        <h3>No tourist spots found</h3>
        <p>Try adjusting your search or filter to find what you're looking for.</p>
        <button onclick="resetSearch()" class="reset-btn">Reset Search</button>
    </div>

    <!-- Pagination -->
    <div class="mt-8 pagination-wrapper" id="pagination-wrapper">
        {{ $places->links() }}
    </div>
</div>

<style>
    /* Search and Filter Bar */
    .search-filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        background: var(--white);
        padding: 20px;
        border-radius: 8px;  /* Changed from 16px to 8px */
        border: 1px solid var(--border);
    }
    
    .search-container {
        flex: 2;
        position: relative;
        min-width: 200px;
    }
    
    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 14px;
    }
    
    .search-input {
        width: 100%;
        padding: 12px 16px 12px 40px;
        border: 1.5px solid var(--border);
        border-radius: 6px;  /* Changed from 12px to 6px */
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.2s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--teal);
        box-shadow: 0 0 0 2px rgba(14,138,110,0.1);
    }
    
    .clear-search {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 14px;
        padding: 4px;
    }
    
    .clear-search:hover {
        color: var(--text-dark);
    }
    
    .filter-group {
        flex: 1;
        display: flex;
        gap: 12px;
        min-width: 250px;
    }
    
    .filter-select {
        flex: 1;
        padding: 12px 16px;
        border: 1.5px solid var(--border);
        border-radius: 6px;  /* Changed from 12px to 6px */
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        background: var(--white);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: var(--teal);
    }
    
    .results-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .search-status {
        font-size: 12px;
        color: var(--teal);
        background: var(--teal-light);
        padding: 4px 12px;
        border-radius: 20px;
    }
    
    /* No Results */
    .no-results {
        text-align: center;
        padding: 60px 20px;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 8px;  /* Changed from 20px to 8px */
        margin-top: 20px;
    }
    
    .no-results i {
        font-size: 64px;
        color: var(--text-muted);
        margin-bottom: 16px;
    }
    
    .no-results h3 {
        font-size: 20px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
    }
    
    .no-results p {
        font-size: 14px;
        color: var(--text-muted);
        margin-bottom: 20px;
    }
    
    .reset-btn {
        padding: 10px 24px;
        background: var(--teal);
        color: white;
        border: none;
        border-radius: 6px;  /* Changed from 10px to 6px */
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .reset-btn:hover {
        background: #0c7a60;
        transform: translateY(-1px);
    }
    
    /* Hide pagination when searching */
    .pagination-wrapper.hidden {
        display: none;
    }
    
    .hidden {
        display: none;
    }
    
    .places-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
    }

    .place-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 8px;  /* Changed from 16px to 8px */
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .place-card:hover {
        transform: translateY(-2px);  /* Reduced from -4px for subtlety */
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);  /* Softer shadow */
    }

    .place-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .place-img-fallback {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #c8e6d8, #a8d4ee);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6aa8c0;
        font-size: 48px;
    }

    .place-body {
        padding: 20px;
    }

    .place-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 8px;
    }

    .place-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    .place-category {
        font-size: 10px;
        font-weight: 600;
        padding: 4px 10px;
        background: var(--teal-light);
        color: var(--teal);
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .place-desc {
        font-size: 13px;
        color: var(--text-mid);
        line-height: 1.55;
        margin-bottom: 12px;
    }

    .place-stats {
        display: flex;
        gap: 16px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }

    .place-stats-item {
        font-size: 11px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .place-stats-item i {
        color: var(--teal);
        font-size: 11px;
    }

    .place-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-route {
        flex: 2;
        padding: 10px;
        background: var(--teal-light);
        color: var(--teal);
        border: none;
        border-radius: 6px;  /* Changed from 10px to 6px */
        font-size: 13px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: background .15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-route:hover {
        background: #b8e0d0;
    }

    .btn-save-place {
        flex: 1;
        padding: 10px;
        background: #fef3c7;
        color: #92400e;
        border: none;
        border-radius: 6px;  /* Changed from 10px to 6px */
        font-size: 13px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all .15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-save-place:hover {
        background: #fde68a;
        transform: translateY(-1px);
    }

    .btn-save-place.saved {
        background: #f59e0b;
        color: #fff;
        cursor: default;
        opacity: 0.8;
    }

    .btn-save-place-disabled {
        flex: 1;
        padding: 10px;
        background: #f3f4f6;
        color: #9ca3af;
        border: none;
        border-radius: 6px;  /* Changed from 10px to 6px */
        font-size: 13px;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    @media (max-width: 768px) {
        .places-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        
        .place-stats {
            flex-direction: column;
            gap: 6px;
        }
        
        .search-filter-bar {
            flex-direction: column;
        }
        
        .filter-group {
            width: 100%;
        }
    }
</style>

@push('scripts')
<script>
    let searchTimeout;
    let currentSearchTerm = '';
    let currentCategory = 'all';
    let currentSort = 'latest';
    
    // DOM Elements
    const searchInput = document.getElementById('search-input');
    const clearSearchBtn = document.getElementById('clear-search');
    const categoryFilter = document.getElementById('category-filter');
    const sortOrder = document.getElementById('sort-order');
    const placesGrid = document.getElementById('places-grid');
    const noResultsDiv = document.getElementById('no-results');
    const paginationWrapper = document.getElementById('pagination-wrapper');
    const resultsCountSpan = document.querySelector('#results-count');
    const searchStatusSpan = document.getElementById('search-status');
    
    // All place cards data
    let allCards = [];
    
    // Initialize - store all cards
    function initCards() {
        allCards = Array.from(document.querySelectorAll('.place-card'));
        applyFilters();
    }
    
    // Search function
    function searchPlaces() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        currentSearchTerm = searchTerm;
        
        if (searchTerm.length > 0) {
            clearSearchBtn.classList.remove('hidden');
            searchStatusSpan.classList.remove('hidden');
            searchStatusSpan.textContent = `Searching for "${searchTerm}"...`;
        } else {
            clearSearchBtn.classList.add('hidden');
            searchStatusSpan.classList.add('hidden');
        }
        
        applyFilters();
    }
    
    // Apply all filters (search, category, sort)
    // Apply all filters (search, category, sort)
function applyFilters() {
    let filteredCards = [...allCards];
    
    // Apply search filter
    if (currentSearchTerm) {
        filteredCards = filteredCards.filter(card => {
            const name = card.dataset.name?.toLowerCase() || '';
            const category = card.dataset.category?.toLowerCase() || '';
            const description = card.dataset.description?.toLowerCase() || '';
            return name.includes(currentSearchTerm) || 
                   category.includes(currentSearchTerm) || 
                   description.includes(currentSearchTerm);
        });
        
        searchStatusSpan.textContent = `Found ${filteredCards.length} result(s) for "${currentSearchTerm}"`;
    }
    
    // Apply category filter
    if (currentCategory !== 'all') {
        filteredCards = filteredCards.filter(card => {
            const category = card.dataset.category || 'Uncategorized';
            return category === currentCategory;
        });
    }
    
    // Apply sorting - requires reordering DOM elements
    if (currentSort !== 'latest') {
        filteredCards = sortCards(filteredCards, currentSort);
    }
    
    // Update UI - hide all cards first, then show only filtered ones
    allCards.forEach(card => {
        card.style.display = 'none';
    });
    
    if (filteredCards.length === 0) {
        noResultsDiv.classList.remove('hidden');
        paginationWrapper.classList.add('hidden');
        placesGrid.style.display = 'none';
    } else {
        filteredCards.forEach(card => {
            card.style.display = 'block';
        });
        noResultsDiv.classList.add('hidden');
        paginationWrapper.classList.add('hidden');
        placesGrid.style.display = 'grid';
        
        // If sorting by name/popularity, reorder the DOM elements
        if (currentSort !== 'latest') {
            filteredCards.forEach(card => {
                placesGrid.appendChild(card);
            });
        }
    }
    
    // Update results count
    updateResultsCount(filteredCards.length);
}
    
    // Sort cards
    function sortCards(cards, sortType) {
        const sorted = [...cards];
        
        switch(sortType) {
            case 'name_asc':
                sorted.sort((a, b) => (a.dataset.name || '').localeCompare(b.dataset.name || ''));
                break;
            case 'name_desc':
                sorted.sort((a, b) => (b.dataset.name || '').localeCompare(a.dataset.name || ''));
                break;
            case 'popular':
                sorted.sort((a, b) => (parseInt(b.dataset.saved) || 0) - (parseInt(a.dataset.saved) || 0));
                break;
            case 'latest':
            default:
                // Keep original order (already from server)
                break;
        }
        
        return sorted;
    }
    
    // Update results count display
    function updateResultsCount(count) {
        if (resultsCountSpan) {
            resultsCountSpan.innerHTML = `<span class="font-semibold text-gray-800">${count}</span>`;
        }
    }
    
    // Re-attach event listeners to cloned buttons
    // Re-attach event listeners to cloned buttons
function reattachEventListeners() {
    // Re-attach route buttons - preserve inline onclick or reattach
    document.querySelectorAll('.btn-route').forEach(btn => {
        // Get the place card
        const card = btn.closest('.place-card');
        if (!card) return;
        
        const lat = card.dataset.lat;
        const lng = card.dataset.lng;
        const name = card.dataset.name;
        
        // Remove existing listeners by cloning
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        // Add fresh click listener
        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            setDestinationFromPlace(parseFloat(lat), parseFloat(lng), name);
        });
    });
    
    // Re-attach save buttons (keep your existing code)
    @auth
    document.querySelectorAll('.save-place-btn').forEach(btn => {
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        newBtn.addEventListener('click', async function() {
            const placeId = this.dataset.id;
            const placeName = this.dataset.name;
            const placeLat = this.dataset.lat;
            const placeLng = this.dataset.lng;
            
            try {
                const response = await fetch('{{ route("user.save-location") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        place_id: placeId,
                        name: placeName,
                        latitude: placeLat,
                        longitude: placeLng
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.innerHTML = '<i class="fas fa-bookmark"></i> Saved';
                    this.classList.add('saved');
                    this.disabled = true;
                    showNotification('Location saved!', 'success');
                }
            } catch (error) {
                showNotification('Error saving location', 'error');
            }
        });
    });
    @endauth
}
    
    // Reset search and filters
    function resetSearch() {
        searchInput.value = '';
        categoryFilter.value = 'all';
        sortOrder.value = 'latest';
        currentSearchTerm = '';
        currentCategory = 'all';
        currentSort = 'latest';
        clearSearchBtn.classList.add('hidden');
        searchStatusSpan.classList.add('hidden');
        
        // Reset to original server-side rendered state
        location.reload();
    }
    
    // Event listeners
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(searchPlaces, 300);
    });
    
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        searchPlaces();
        searchInput.focus();
    });
    
    categoryFilter.addEventListener('change', function() {
        currentCategory = this.value;
        applyFilters();
    });
    
    sortOrder.addEventListener('change', function() {
        currentSort = this.value;
        applyFilters();
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initCards();
    });

    function setDestinationFromPlace(lat, lng, name) {
        window.location.href = `/?dest_lat=${lat}&dest_lng=${lng}&dest_name=${encodeURIComponent(name)}`;
    }

    @auth
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 z-50 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium transition-all duration-300 ${
            type === 'success' ? 'bg-emerald-500' : 
            type === 'error' ? 'bg-red-500' : 
            'bg-blue-500'
        }`;
        notification.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>${message}`;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
    @endauth

    function showLoginMessage() {
        showNotification('Please login to save locations', 'info');
    }
</script>
@endpush
@endsection