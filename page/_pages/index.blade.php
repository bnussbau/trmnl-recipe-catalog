<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TRMNL Catalog</title>
    <link rel="stylesheet" href="{{ Asset::get('app.css') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=eb-garamond:500&family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <script>
        // Set initial theme based on browser preference before page renders to prevent flash
        (function() {
            const savedTheme = localStorage.getItem('theme');
            let initialTheme = 'light';
            
            if (savedTheme) {
                initialTheme = savedTheme;
            } else {
                // Check browser preference
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    initialTheme = 'dark';
                }
            }
            
            document.documentElement.setAttribute('data-theme', initialTheme);
        })();
    </script>
    <style>
        [data-theme="dark"] {
            --bg-color: #1a1a1a;
            --text-color: #e5e5e5;
            --card-bg: #262626;
            --card-bg-hover: #2a2a2a;
            --border-color: #404040;
            --secondary-text: #a3a3a3;
        }
        [data-theme="light"] {
            --bg-color: #e7e7e7;
            --text-color: #141414;
            --card-bg: #fafafa;
            --card-bg-hover: #ffffff;
            --border-color: #d1d5db;
            --secondary-text: #4b5563;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
        }
    </style>
</head>

<body class="antialiased">
    @php
        $catalogService = new \App\Services\CatalogService();
        $entries = $catalogService->getEntries();
        $categoryData = $catalogService->getCategories();
        $categories = $categoryData['categories'];
        $categoryCounts = $categoryData['counts'];
    @endphp

    <header class="bg-[var(--card-bg)] py-16 mb-10 relative">
        <div class="max-w-7xl mx-auto px-5 relative">
            <button id="themeToggle" class="absolute top-5 right-5 bg-transparent border border-[var(--border-color)] p-2 rounded-full hover:border-[#f8654b] hover:text-[#f8654b] transition-all" aria-label="Toggle dark mode">
                <svg id="themeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </button>
            
            <h1 class="font-eb-garamond text-5xl md:text-6xl font-medium mb-2 tracking-tight">OSS Recipe Catalog for TRMNL</h1>
            <div class="flex flex-col md:flex-row md:items-start md:gap-4 mb-8">
                <p class="text-[var(--secondary-text)] text-base flex-1">
                    A community-driven catalog of repositories containing recipes for TRMNL.<br>
                    The goal of this project is to collect TRMNLP-compatible recipes in public repositories and make them available as a catalog for BYOS.
                </p>
                <a href="https://github.com/bnussbau/trmnl-recipe-catalog/blob/main/CONTRIBUTING.md" target="_blank" class="inline-block px-5 py-2.5 bg-[#f8654b] text-white rounded-full font-medium text-sm hover:bg-[#e5553b] transition-colors whitespace-nowrap md:mt-0 mt-3">
                    Add a recipe
                </a>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div class="flex flex-wrap gap-2">
                    <button class="filter-btn active px-5 py-2 rounded-full border border-[#f8654b] font-medium text-sm hover:text-white transition-all bg-[#f8654b] text-white hover:border-[#f8654b]" data-filter="all">
                        All
                    </button>
                    @foreach($categories as $category)
                        <button class="filter-btn px-5 py-2 rounded-full border border-[var(--border-color)] text-[var(--secondary-text)] font-medium text-sm hover:border-[#f8654b] hover:text-[#f8654b] transition-all" data-filter="{{ $category }}">
                            {{ ucfirst($category) }}
                            <span class="filter-count opacity-80 text-xs ml-1 font-normal">({{ $categoryCounts[$category] }})</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-5">
        <div class="flex justify-between items-center gap-3 mb-5">
            <div class="flex items-center gap-3">
                <span id="pluginCount" class="text-sm font-medium text-[var(--text-color)]">{{ count($entries) }} plugins</span>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="byosToggle" class="sr-only">
                    <div id="toggleBg" class="relative inline-flex items-center h-6 w-11 rounded-full bg-gray-300 dark:bg-gray-600 transition-colors focus-within:ring-2 focus-within:ring-[#f8654b] focus-within:ring-opacity-50">
                        <span id="toggleDot" class="inline-block h-4 w-4 transform bg-white rounded-full transition-transform translate-x-1"></span>
                    </div>
                    <span class="text-sm font-medium text-[var(--text-color)]">BYOS compatible only</span>
                </label>
            </div>
            <div class="relative flex-1 max-w-md">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" id="searchInput" placeholder="Search by name, author, ID, or key..." class="w-full bg-[var(--card-bg)] border border-transparent rounded-full pl-10 pr-5 py-2.5 font-medium text-sm text-[var(--text-color)] transition-all hover:bg-[var(--card-bg-hover)] shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#f8654b] focus:ring-opacity-50">
            </div>
            <select id="sortSelect" class="appearance-none bg-[var(--card-bg)] border border-transparent rounded-full px-5 py-2.5 pr-10 font-medium text-sm text-[var(--text-color)] cursor-pointer transition-all hover:bg-[var(--card-bg-hover)] shadow-sm hover:shadow-md focus:outline-none" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%236b7280\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'%3E%3C/path%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 16px center; background-size: 16px;">
                <option value="name" selected>Name</option>
                <option value="author">Author</option>
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-16" id="pluginGrid">
            @foreach($entries as $entry)
                @php
                    $entryCategories = $entry['author_bio']['category'] ?? '';
                    $categoryList = $entryCategories ? array_map('trim', explode(',', $entryCategories)) : [];
                @endphp
                <div class="plugin-card bg-[var(--card-bg)] rounded-xl overflow-hidden transition-all hover:-translate-y-0.5 hover:shadow-lg border border-transparent hover:bg-[var(--card-bg-hover)] flex flex-col" data-name="{{ strtolower($entry['name']) }}" data-categories="{{ implode(',', $categoryList) }}" data-author="{{ strtolower($entry['author']['github'] ?? '') }}" data-id="{{ $entry['id'] ?? '' }}" data-key="{{ strtolower($entry['key']) }}" data-byos-compatible="{{ $entry['byos_laravel']['compatibility'] ? 'true' : 'false' }}">
                    <div class="w-full h-44 bg-transparent flex items-center justify-center overflow-hidden">
                        @if($entry['screenshot_url'])
                            <img src="{{ $entry['screenshot_url'] }}" alt="{{ $entry['name'] }} Screenshot" class="w-full h-full object-contain cursor-pointer hover:opacity-90 transition-opacity" loading="lazy" onclick="openScreenshotModal('{{ $entry['screenshot_url'] }}', '{{ $entry['name'] }}')">
                        @elseif($entry['logo_url'])
                            <img src="{{ $entry['logo_url'] }}" alt="{{ $entry['name'] }} Logo" class="w-full h-full object-contain" loading="lazy">
                        @else
                            <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-start gap-3 mb-3">
                            @if($entry['logo_url'])
                                <img src="{{ $entry['logo_url'] }}" alt="{{ $entry['name'] }} Icon" class="w-12 h-12 object-cover flex-shrink-0 rounded" loading="lazy">
                            @else
                                <div class="w-12 h-12 bg-gray-300 dark:bg-gray-600 rounded flex-shrink-0 flex items-center justify-center">
                                    <span class="text-gray-600 dark:text-gray-300 font-bold text-lg">{{ substr($entry['name'], 0, 1) }}</span>
                                </div>
                            @endif
                            <h3 class="text-lg font-semibold leading-tight text-[var(--text-color)]">{{ $entry['name'] }}</h3>
                        </div>
                        <div class="text-xs text-[var(--secondary-text)] mb-3 flex items-center gap-1.5 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.7;">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            {{ $entry['author']['github'] ?: 'Unknown' }}
                        </div>
                        <div class="text-sm text-[var(--secondary-text)] mb-5 flex-1">
                            @if($entry['author_bio']['description'])
                                @php
                                    $description = $entry['author_bio']['description'];
                                    $truncated = strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                                @endphp
                                {{ $truncated }}
                            @else
                                <span class="italic">No description available</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-3 mt-auto pt-3 border-t border-[var(--border-color)]">
                            @if($entry['id'])
                                <button onclick="openCompatibilityModal({{ json_encode($entry) }})" class="flex items-center gap-1.5 text-[#f8654b] hover:text-[#e5553b] transition-colors text-sm font-medium" title="Install & Compatibility">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 3 3m0 0 3-3m-3 3v-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span>Install</span>
                                </button>
                            @endif
                            @if($entry['repo'])
                                <a href="{{ $entry['repo'] }}" target="_blank" rel="noopener noreferrer" class="text-[#f8654b] hover:text-[#e5553b] transition-colors" title="Repository">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="w-4 h-4">
                                        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8"></path>
                                    </svg>
                                </a>
                            @endif
                            @if($entry['zip_url'])
                                <a href="{{ $entry['zip_url'] }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1.5 text-[#f8654b] hover:text-[#e5553b] transition-colors text-sm font-medium" title="Download ZIP">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                    </svg>
                                    <span>ZIP</span>
                                </a>
                            @endif
                            @if($entry['author_bio']['learn_more_url'])
                                <a href="{{ $entry['author_bio']['learn_more_url'] }}" target="_blank" rel="noopener noreferrer" class="text-[#f8654b] hover:text-[#e5553b] transition-colors" title="Learn More">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                        <path d="M12 17h.01"></path>
                                    </svg>
                                </a>
                            @endif
                            @if(!empty($entry['funding']) && (isset($entry['funding']['custom']) || count(array_filter($entry['funding'])) > 0))
                                <button onclick="openFundingModal({{ json_encode($entry['funding']) }})" class="flex items-center gap-1.5 text-[#f8654b] hover:text-[#e5553b] transition-colors text-sm font-medium" title="Funding">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                                    </svg>
                                    <span>Fund</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Screenshot Modal -->
    <div id="screenshotModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4" onclick="closeScreenshotModal(event)">
        <div class="relative max-w-7xl max-h-full">
            <button onclick="closeScreenshotModal(event)" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl font-bold">&times;</button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-[90vh] object-contain">
        </div>
    </div>

    <!-- Compatibility Modal -->
    <div id="compatibilityModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4" onclick="closeCompatibilityModal(event)">
        <div class="relative bg-[var(--card-bg)] rounded-xl p-6 max-w-md w-full mx-4" onclick="event.stopPropagation()">
            <button onclick="closeCompatibilityModal(event)" class="absolute top-4 right-4 text-[var(--text-color)] hover:text-[var(--secondary-text)] text-2xl font-bold">&times;</button>
            <h3 class="text-xl font-semibold mb-4 text-[var(--text-color)]">Compatibility & Install</h3>
            <div id="compatibilityContent" class="space-y-3"></div>
        </div>
    </div>

    <!-- Funding Modal -->
    <div id="fundingModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4" onclick="closeFundingModal(event)">
        <div class="relative bg-[var(--card-bg)] rounded-xl p-6 max-w-md w-full mx-4" onclick="event.stopPropagation()">
            <button onclick="closeFundingModal(event)" class="absolute top-4 right-4 text-[var(--text-color)] hover:text-[var(--secondary-text)] text-2xl font-bold">&times;</button>
            <h3 class="text-xl font-semibold mb-4 text-[var(--text-color)]">Funding Options</h3>
            <div id="fundingContent" class="space-y-2"></div>
        </div>
    </div>

    <script>
        // Screenshot Modal
        function openScreenshotModal(imageUrl, imageAlt) {
            const modal = document.getElementById('screenshotModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageUrl;
            modalImage.alt = imageAlt;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeScreenshotModal(event) {
            if (event.target.id === 'screenshotModal' || event.target.tagName === 'BUTTON') {
                const modal = document.getElementById('screenshotModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        }

        // Compatibility Modal
        function openCompatibilityModal(entry) {
            const modal = document.getElementById('compatibilityModal');
            const content = document.getElementById('compatibilityContent');
            
            let html = '<div class="space-y-3">';
            
            // TRMNL Core
            if (entry.id) {
                const checkIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`;
                html += `<div class="flex items-start gap-3">
                    <span class="flex-shrink-0 mt-0.5">${checkIcon}</span>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-[var(--text-color)]">TRMNL Core (Cloud service)</div>
                        <a href="https://trmnl.com/recipes/${entry.id}" target="_blank" class="text-sm text-[#f8654b] hover:text-[#e5553b] hover:underline">Install</a>
                    </div>
                </div>`;
            }
            
            // BYOS Laravel
            const byos = entry.byos_laravel || {};
            const byosCompatible = byos.compatibility === true;
            const byosIcon = byosCompatible 
                ? `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>`;
            
            let byosText = 'BYOS Laravel';
            if (byosCompatible && byos.min_version) {
                byosText += ` (min. version: ${byos.min_version})`;
            } else if (!byosCompatible && byos.compatibility_note) {
                byosText += ` – ${byos.compatibility_note}`;
            }
            
            html += `<div class="flex items-start gap-3">
                <span class="flex-shrink-0 mt-0.5">${byosIcon}</span>
                <div class="flex-1">
                    <div class="text-sm font-medium text-[var(--text-color)]">${byosText}</div>`;
            
            if (byosCompatible && byos.installation_instructions) {
                html += `<div class="text-sm text-[var(--secondary-text)] mt-1">${byos.installation_instructions}</div>`;
            }
            
            html += `</div>
            </div>`;
            
            html += '</div>';
            content.innerHTML = html;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeCompatibilityModal(event) {
            if (event.target.id === 'compatibilityModal' || event.target.tagName === 'BUTTON') {
                const modal = document.getElementById('compatibilityModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        }

        // Funding Modal
        function openFundingModal(funding) {
            const modal = document.getElementById('fundingModal');
            const content = document.getElementById('fundingContent');
            
            let html = '<div class="space-y-2">';
            
            const urlMap = {
                'community_bridge': (username) => `https://funding.communitybridge.org/projects/${username}`,
                'github': (username) => `https://github.com/sponsors/${username}`,
                'issuehunt': (username) => `https://issuehunt.io/r/${username}`,
                'ko_fi': (username) => `https://ko-fi.com/${username}`,
                'liberapay': (username) => `https://liberapay.com/${username}`,
                'open_collective': (username) => `https://opencollective.com/${username}`,
                'patreon': (username) => `https://patreon.com/${username}`,
                'tidelift': (username) => `https://tidelift.com/subscription/pkg/${username}`,
                'polar': (username) => `https://polar.sh/${username}`,
                'buy_me_a_coffee': (username) => `https://buymeacoffee.com/${username}`,
                'thanks_dev': (username) => `https://thanks.dev/u/gh/${username}`,
            };
            
            Object.entries(funding).forEach(([platform, username]) => {
                if (username) {
                    const url = platform === 'custom' ? username : (urlMap[platform] ? urlMap[platform](username) : '#');
                    const displayName = platform === 'custom' ? username : platform.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    html += `<a href="${url}" target="_blank" rel="noopener noreferrer" class="block px-4 py-3 text-sm text-[var(--text-color)] hover:bg-[var(--card-bg-hover)] border border-[var(--border-color)] rounded-lg transition-colors">
                        ${displayName}
                    </a>`;
                }
            });
            
            html += '</div>';
            content.innerHTML = html;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeFundingModal(event) {
            if (event.target.id === 'fundingModal' || event.target.tagName === 'BUTTON') {
                const modal = document.getElementById('fundingModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        }

        // Close modals on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const screenshotModal = document.getElementById('screenshotModal');
                const compatibilityModal = document.getElementById('compatibilityModal');
                const fundingModal = document.getElementById('fundingModal');
                if (!screenshotModal.classList.contains('hidden')) {
                    closeScreenshotModal({ target: { id: 'screenshotModal' } });
                }
                if (!compatibilityModal.classList.contains('hidden')) {
                    closeCompatibilityModal({ target: { id: 'compatibilityModal' } });
                }
                if (!fundingModal.classList.contains('hidden')) {
                    closeFundingModal({ target: { id: 'fundingModal' } });
                }
            }
        });
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const html = document.documentElement;
        
        const sunIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>`;
        const moonIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>`;

        function setTheme(theme) {
            html.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            themeIcon.outerHTML = theme === 'dark' ? sunIcon : moonIcon;
        }

        // Get initial theme: check localStorage first, then browser preference, then default to light
        function getInitialTheme() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                return savedTheme;
            }
            
            // Check browser preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                return 'dark';
            }
            
            return 'light';
        }

        const initialTheme = getInitialTheme();
        setTheme(initialTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        });

        // Filter functionality
        const filterBtns = document.querySelectorAll('.filter-btn');
        const pluginGrid = document.getElementById('pluginGrid');
        const searchInput = document.getElementById('searchInput');
        const byosToggle = document.getElementById('byosToggle');
        let currentCategory = 'all';
        let currentSearch = '';
        let byosOnly = false;
        
        // Initialize active button filter count color
        const activeBtn = document.querySelector('.filter-btn.active');
        if (activeBtn) {
            const count = activeBtn.querySelector('.filter-count');
            if (count) {
                count.classList.add('text-white');
                count.classList.remove('opacity-80');
            }
        }

        function filterPlugins() {
            const cards = Array.from(pluginGrid.querySelectorAll('.plugin-card'));
            let visibleCount = 0;
            
            cards.forEach(card => {
                // Category filter
                let categoryMatch = true;
                if (currentCategory !== 'all') {
                    const categories = (card.dataset.categories || '').split(',').map(c => c.trim());
                    categoryMatch = categories.includes(currentCategory);
                }
                
                // Search filter
                let searchMatch = true;
                if (currentSearch.trim()) {
                    const searchTerm = currentSearch.toLowerCase().trim();
                    const name = (card.dataset.name || '').toLowerCase();
                    const author = (card.dataset.author || '').toLowerCase();
                    const id = (card.dataset.id || '').toString();
                    const key = (card.dataset.key || '').toLowerCase();
                    
                    searchMatch = name.includes(searchTerm) || 
                                 author.includes(searchTerm) || 
                                 id.includes(searchTerm) || 
                                 key.includes(searchTerm);
                }
                
                // BYOS filter
                let byosMatch = true;
                if (byosOnly) {
                    byosMatch = card.dataset.byosCompatible === 'true';
                }
                
                // Show card only if all filters match
                if (categoryMatch && searchMatch && byosMatch) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Update plugin count
            const pluginCountEl = document.getElementById('pluginCount');
            if (pluginCountEl) {
                pluginCountEl.textContent = `${visibleCount} plugin${visibleCount !== 1 ? 's' : ''}`;
            }
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                filterBtns.forEach(b => {
                    b.classList.remove('active', 'bg-[#f8654b]', 'text-white', 'border-[#f8654b]', 'hover:text-white');
                    b.classList.add('text-[var(--secondary-text)]', 'border-[var(--border-color)]', 'hover:text-[#f8654b]');
                    // Update filter count color
                    const count = b.querySelector('.filter-count');
                    if (count) {
                        count.classList.remove('text-white');
                        count.classList.add('opacity-80');
                    }
                });
                
                // Add active class to clicked button
                btn.classList.add('active', 'bg-[#f8654b]', 'text-white', 'border-[#f8654b]', 'hover:text-white');
                btn.classList.remove('text-[var(--secondary-text)]', 'border-[var(--border-color)]', 'hover:text-[#f8654b]');
                // Update filter count color for active button
                const count = btn.querySelector('.filter-count');
                if (count) {
                    count.classList.add('text-white');
                    count.classList.remove('opacity-80');
                }
                
                currentCategory = btn.dataset.filter;
                filterPlugins();
                sortPlugins();
            });
        });

        // Search functionality
        searchInput.addEventListener('input', (e) => {
            currentSearch = e.target.value;
            filterPlugins();
            sortPlugins();
        });

        // BYOS Toggle functionality
        const toggleBg = document.getElementById('toggleBg');
        const toggleDot = document.getElementById('toggleDot');
        
        byosToggle.addEventListener('change', (e) => {
            byosOnly = e.target.checked;
            if (byosOnly) {
                toggleBg.classList.add('bg-[#f8654b]');
                toggleBg.classList.remove('bg-gray-300', 'dark:bg-gray-600');
                toggleDot.classList.remove('translate-x-1');
                toggleDot.classList.add('translate-x-6');
            } else {
                toggleBg.classList.remove('bg-[#f8654b]');
                toggleBg.classList.add('bg-gray-300', 'dark:bg-gray-600');
                toggleDot.classList.remove('translate-x-6');
                toggleDot.classList.add('translate-x-1');
            }
            filterPlugins();
            sortPlugins();
        });

        // Sort functionality
        const sortSelect = document.getElementById('sortSelect');
        let currentSort = 'name';

        function sortPlugins() {
            const cards = Array.from(pluginGrid.querySelectorAll('.plugin-card:not([style*="display: none"])'));
            
            cards.sort((a, b) => {
                if (currentSort === 'author') {
                    const authorA = (a.dataset.author || '').toLowerCase();
                    const authorB = (b.dataset.author || '').toLowerCase();
                    return authorA.localeCompare(authorB);
                } else {
                    // Default: name
                    const nameA = a.dataset.name.toLowerCase();
                    const nameB = b.dataset.name.toLowerCase();
                    return nameA.localeCompare(nameB);
                }
            });
            
            cards.forEach(card => pluginGrid.appendChild(card));
        }

        sortSelect.addEventListener('change', (e) => {
            currentSort = e.target.value;
            sortPlugins();
        });
        
        // Initial sort on page load
        sortPlugins();
    </script>
</body>

</html>
