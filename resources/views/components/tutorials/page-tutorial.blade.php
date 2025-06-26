{{-- Tutorial Component View --}}
@props(['pageName'])

@php
    $allTutorials = \App\Models\Page_Tutorials::with('page')
        ->where('is_active', true)
        ->orderBy('order', 'asc') // Menambahkan pengurutan berdasarkan order ascending
        ->get()
        ->groupBy(function ($tutorial) {
            return $tutorial->page->name;
        });
@endphp

<style>
    .tutorial-wrapper {
        position: fixed;
        bottom: 45px;
        right: 20px;
        z-index: 1000;
        max-height: calc(100vh - 100px);
        overflow-y: auto;
        transition: all 0.3s ease;
        border: none;
    }

    .tutorial-container {
        background: #ffde9e;
        border-radius: 12px;
        width: 600px;
        max-width: 90vw;
        display: flex;
        flex-direction: column;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        /* Tambahkan ini untuk memastikan konten tidak keluar dari border-radius */
    }

    .tutorial-sidebar {
        width: 100%;
        max-height: 35vh;
        overflow-y: auto;
        padding: 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .tutorial-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .tutorial-sidebar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .tutorial-content {
        flex: 1;
        padding: 1.5rem;
        padding-top: 2rem;
        /* Added extra padding at the top */
        overflow-y: auto;
        background: #ffde9e;
        display: flex;
        flex-direction: column;
    }

    .page-section {
        margin-bottom: 1rem;
        background-color: #ffde9e;
    }

    .page-title {
        cursor: pointer;
        padding: 0.75rem 1rem;
        background: #ffe8d6;
        border-radius: 6px;
        margin: 0;
        font-size: 0.95rem;
        font-weight: 600;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.2s ease;
    }

    .page-title:hover {
        background: #ffcc00;
    }

    .tutorial-list {
        padding: 0.75rem 0 0.75rem 2rem;
    }

    .tutorial-item {
        padding: 0.625rem 1rem;
        cursor: pointer;
        color: #64748b;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        border-radius: 4px;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tutorial-item:hover {
        background: #f1f5f9;
        color: #334155;
    }

    .order-number {
        color: #000000;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Header Styles */
    .tutorial-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .step-counter {
        color: #ffde9e;
        font-size: 0.875rem;
        font-weight: 500;
        text-align: center;
        flex: 1;
        display: flex;
        justify-content: center;
    }

    .tutorial-media {
        margin-top: 0.5rem;
        /* Add some space at the top */
        margin-bottom: 1.5rem;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        position: relative;
        width: 100%;
        height: auto;
        max-height: 45vh;
        /* Adjusted height to fit better when positioned lower */
    }

    .tutorial-video,
    .tutorial-image {
        width: 100%;
        height: auto;
        max-height: 45vh;
        /* Match the container max-height */
        object-fit: contain;
    }

    .help-button {
        position: fixed;
        bottom: 45px;
        /* Reduced from 130px to match the new container position */
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #3b82f6;
        color: white;
        border: none;
        font-size: 24px;
        cursor: pointer;
        z-index: 999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .help-button i {
        font-size: inherit;
    }

    .help-button:hover {
        background: #2563eb;
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }

    .close-btn {
        background: transparent;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .close-btn:hover {
        background: #f1f5f9;
        color: #334155;
    }

    /* Tutorial Description */
    .tutorial-description {
        color: #000000;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    @media screen and (max-height: 668px) {
        .tutorial-wrapper {
            max-height: 80vh;
            bottom: 80px;
            /* Adjusted for smaller screens */
        }

        .help-button {
            bottom: 50px;
        }
    }

    @media screen and (max-width: 576px) {
        .tutorial-container {
            width: calc(100vw - 32px);
        }

        .tutorial-content {
            padding: 1rem;
        }

        .tutorial-sidebar {
            max-height: 40vh;
        }

        .help-button {
            width: 48px;
            height: 48px;
            right: a16px;
            font-size: 20px;
            bottom: 70px;
            /* Increased from 20px to ensure it's above footer */
            z-index: 1001;
            /* Ensure it's above the footer */
        }

        .tutorial-wrapper {
            bottom: 70px;
            /* Increased from 45px to match help-button */
        }
    }

    .tutorial-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.5rem;
        gap: 1rem;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        gap: 0.5rem;
        min-width: 120px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .action-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        box-shadow: none;
    }

    /* Previous Button - Soft purple/blue gradient */
    .prev-btn {
        background: linear-gradient(to right, #E2E8F0, #CBD5E1);
        color: #4B5563;
        border: 1px solid #CBD5E1;
    }

    .prev-btn:hover:not(:disabled) {
        background: linear-gradient(to right, #CBD5E1, #94A3B8);
        color: #1E293B;
        border-color: #94A3B8;
    }

    .prev-btn .btn-icon {
        color: #6366F1;
    }

    /* Continue Button - Vibrant blue gradient */
    .next-btn {
        background: linear-gradient(to right, #3B82F6, #2563EB);
        color: white;
        border: 1px solid #2563EB;
    }

    .next-btn:hover:not(:disabled) {
        background: linear-gradient(to right, #2563EB, #1D4ED8);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
    }

    .next-btn .btn-icon {
        color: #DBEAFE;
    }

    .btn-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }

    .btn-text {
        font-size: 0.95rem;
    }

    @media screen and (max-width: 576px) {
        .action-btn {
            min-width: auto;
            padding: 0.65rem 1rem;
        }
    }
</style>

@if ($allTutorials->isNotEmpty())
    <div class="tutorial-wrapper" id="tutorial-wrapper" style="display: none;">
        <div class="tutorial-container">
            <main class="tutorial-content" id="tutorial-content">
                <header class="tutorial-header">
                    <h3 id="current-tutorial-title">Select a Tutorial</h3>
                    <div class="header-right">
                        <button class="close-btn" aria-label="Close tutorial">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </header>

                <div id="tutorial-media-container" class="tutorial-media">
                    <div class="media-placeholder">
                        <div class="text-center">
                            <i class="fas fa-images"></i>
                            <p>Select a tutorial to view content</p>
                        </div>
                    </div>
                </div>

                <div id="tutorial-content-wrapper">
                    <p id="tutorial-description" class="tutorial-description">
                        Please select a tutorial from the list below to begin.
                    </p>
                    <div class="tutorial-actions" id="tutorial-actions">
                        <div class="tutorial-actions" id="tutorial-actions">
                            <button class="action-btn prev-btn" id="previous-btn" style="display: none;">
                                <i class="fas fa-backward"></i> Previous
                            </button>
                            <span class="step-counter" id="step-counter"></span>
                            <button class="action-btn next-btn" id="continue-btn" style="display: none;">
                                Continue <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </main>

            <aside class="tutorial-sidebar" style="background-color: #ffde9e;">
                <nav class="tutorial-pages" style="background-color: #ffde9e;">
                    @foreach ($allTutorials as $pageName => $pageTutorials)
                        <div class="page-section" style="background-color: #ffde9e;">
                            <h4 class="page-title" data-page="{{ $pageName }}">
                                <i class="fas fa-chevron-right transition-transform"></i>
                                {{ ucfirst($pageName) }}
                            </h4>
                            <div class="tutorial-list" id="tutorials-{{ $pageName }}" style="display: none;">
                                @foreach ($pageTutorials->sortBy('order') as $tutorial)
                                    <button class="tutorial-item w-full text-left text-dark "
                                        data-tutorial-id="{{ $tutorial->id }}"
                                        data-element="{{ $tutorial->element_selector }}"
                                        data-position="{{ $tutorial->position }}" data-order="{{ $tutorial->order }}"
                                        data-page="{{ $pageName }}">
                                        <span class="order-number">#{{ $tutorial->order }}</span>
                                        {{ $tutorial->title }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </nav>
            </aside>

        </div>
    </div>

    <button id="help-button" class="help-button" aria-label="Show tutorial">
        <i class="fas fa-question-circle"></i>
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeTutorial();
        });

        function initializeTutorial() {
            document.querySelector('#help-button').addEventListener('click', toggleTutorial);
            document.querySelector('.close-btn').addEventListener('click', toggleTutorial);

            document.querySelectorAll('.page-title').forEach(title => {
                title.addEventListener('click', () => {
                    const pageName = title.dataset.page;
                    togglePageTutorials(pageName);
                });
            });

            document.querySelectorAll('.tutorial-item').forEach((item) => {
                item.addEventListener('click', () => {
                    const tutorialId = item.dataset.tutorialId;
                    const element = item.dataset.element;
                    const position = item.dataset.position;
                    const pageName = item.dataset.page;

                    // Get all tutorials for the current page
                    const currentPageTutorials = Array.from(document.querySelectorAll(
                        `.tutorial-item[data-page="${pageName}"]`));

                    // Find the index of the clicked tutorial within its page
                    const index = currentPageTutorials.indexOf(item);

                    // Update step counter for this page only
                    const stepCounter = document.getElementById('step-counter');
                    stepCounter.textContent = `${index + 1}/${currentPageTutorials.length}`;

                    // Make step counter visible when a tutorial is selected
                    stepCounter.classList.add('active');

                    // Update the navigation state
                    updateNavigationState(index, currentPageTutorials.length, pageName);

                    // Show the tutorial content
                    showTutorialContent(tutorialId, element, position);
                });
            });
        }

        function updateNavigationState(currentIndex, totalTutorials, pageName) {
            const previousBtn = document.getElementById('previous-btn');
            const continueBtn = document.getElementById('continue-btn');

            // Show navigation buttons
            previousBtn.style.display = "inline-block";
            continueBtn.style.display = "inline-block";

            // Store current state in data attributes for navigation
            previousBtn.dataset.currentIndex = currentIndex;
            previousBtn.dataset.currentPage = pageName;
            continueBtn.dataset.currentIndex = currentIndex;
            continueBtn.dataset.currentPage = pageName;

            // Disable previous button if we're on the first tutorial
            previousBtn.disabled = currentIndex === 0;

            // Disable continue button if we're on the last tutorial
            continueBtn.disabled = currentIndex === totalTutorials - 1;
        }

        function toggleTutorial() {
            const wrapper = document.getElementById('tutorial-wrapper');
            const mediaContainer = document.getElementById('tutorial-media-container');
            const tutorialTitle = document.getElementById('current-tutorial-title');
            const tutorialDescription = document.getElementById('tutorial-description');
            const previousBtn = document.getElementById('previous-btn');
            const continueBtn = document.getElementById('continue-btn');
            const stepCounter = document.getElementById('step-counter');

            if (wrapper.style.display === 'none' || wrapper.style.display === '') {
                wrapper.style.display = 'block';

                // Reset to default view
                tutorialTitle.textContent = "Select a Tutorial";
                tutorialDescription.textContent = "Please select a tutorial from the list below to begin.";
                mediaContainer.innerHTML = `
                    <div class="media-placeholder">
                        <div class="text-center">
                            <i class="fas fa-images"></i>
                            <p>Select a tutorial to view content</p>
                        </div>
                    </div>
                `;

                // Hide navigation buttons on initial view
                previousBtn.style.display = "none";
                continueBtn.style.display = "none";

                // Hide step counter on initial view
                stepCounter.classList.remove('active');

                // Reset all page lists to hidden
                document.querySelectorAll('.tutorial-list').forEach(list => {
                    list.style.display = 'none';
                });

                // Reset all chevrons to default position
                document.querySelectorAll('.page-title i').forEach(chevron => {
                    chevron.style.transform = 'rotate(0deg)';
                });
            } else {
                wrapper.style.display = 'none';
            }
        }

        function togglePageTutorials(pageName) {
            const tutorialList = document.getElementById(`tutorials-${pageName}`);
            const chevron = tutorialList.previousElementSibling.querySelector('i');

            document.querySelectorAll('.tutorial-list').forEach(list => {
                if (list.id !== `tutorials-${pageName}`) {
                    list.style.display = 'none';
                    list.previousElementSibling.querySelector('i').style.transform = 'rotate(0deg)';
                }
            });

            const isVisible = tutorialList.style.display === 'block';
            tutorialList.style.display = isVisible ? 'none' : 'block';
            chevron.style.transform = isVisible ? 'rotate(0deg)' : 'rotate(90deg)';
        }

        async function showTutorialContent(tutorialId, elementSelector, position) {
            try {
                const response = await fetch(`{{ url('/get') }}/${tutorialId}`);
                if (!response.ok) throw new Error('Network response was not ok');

                const tutorial = await response.json();

                document.getElementById('current-tutorial-title').textContent = tutorial.title;
                document.getElementById('tutorial-description').textContent = tutorial.description || '';

                const mediaContainer = document.getElementById('tutorial-media-container');
                mediaContainer.innerHTML = '';

                if (tutorial.media_path) {
                    const mediaElement = tutorial.media_type === 'video' ?
                        createVideoElement(tutorial) :
                        createImageElement(tutorial);
                    mediaContainer.appendChild(mediaElement);
                }

                // Position tutorial relative to target element if provided
                if (elementSelector) {
                    const targetElement = document.querySelector(elementSelector);
                    if (targetElement) {
                        positionTutorial(targetElement, position);
                    }
                }
            } catch (error) {
                console.error('Error loading tutorial:', error);
                alert('Failed to load tutorial content. Please try again.');
            }
        }

        function positionTutorial(targetElement, position) {
            const tutorial = document.getElementById('tutorial-wrapper');
            const rect = targetElement.getBoundingClientRect();

            switch (position) {
                case 'top':
                    tutorial.style.bottom = 'auto';
                    tutorial.style.top = `${rect.top - tutorial.offsetHeight - 10}px`;
                    tutorial.style.left = `${rect.left + (rect.width/2) - (tutorial.offsetWidth/2)}px`;
                    break;
                case 'bottom':
                    tutorial.style.bottom = 'auto';
                    tutorial.style.top = `${rect.bottom + 10}px`;
                    tutorial.style.left = `${rect.left + (rect.width/2) - (tutorial.offsetWidth/2)}px`;
                    break;
                case 'left':
                    tutorial.style.right = 'auto';
                    tutorial.style.top = `${rect.top + (rect.height/2) - (tutorial.offsetHeight/2)}px`;
                    tutorial.style.left = `${rect.left - tutorial.offsetWidth - 10}px`;
                    break;
                case 'right':
                    tutorial.style.right = 'auto';
                    tutorial.style.top = `${rect.top + (rect.height/2) - (tutorial.offsetHeight/2)}px`;
                    tutorial.style.left = `${rect.right + 10}px`;
                    break;
                default:
                    // Default position (bottom right)
                    tutorial.style.bottom = '150px';
                    tutorial.style.right = '20px';
                    tutorial.style.left = 'auto';
                    tutorial.style.top = 'auto';
            }
        }

        function createVideoElement(tutorial) {
            const video = document.createElement('video');
            video.className = 'tutorial-video';
            video.controls = true;

            const source = document.createElement('source');
            source.src = `{{ asset('storage') }}/${tutorial.media_path}`;
            source.type = 'video/mp4';

            video.appendChild(source);
            return video;
        }

        function createImageElement(tutorial) {
            const img = document.createElement('img');
            img.className = 'tutorial-image';
            img.src = `{{ asset('storage') }}/${tutorial.media_path}`;
            img.alt = tutorial.title;
            img.style.cursor = 'pointer'; // Menjadikan kursor berbentuk tangan saat hover

            // Tambahkan event listener untuk membuka gambar di tab baru saat diklik
            img.onclick = function() {
                window.open(img.src, '_blank');
            };

            return img;
        }

        // Button previous and continue handlers
        document.addEventListener('DOMContentLoaded', function() {
            const previousBtn = document.getElementById('previous-btn');
            const continueBtn = document.getElementById('continue-btn');

            previousBtn.addEventListener('click', function() {
                const currentIndex = parseInt(previousBtn.dataset.currentIndex);
                const currentPage = previousBtn.dataset.currentPage;

                if (currentIndex > 0) {
                    // Get all tutorials for the current page
                    const pageTutorials = document.querySelectorAll(
                        `.tutorial-item[data-page="${currentPage}"]`);

                    // Click the previous tutorial in this page
                    pageTutorials[currentIndex - 1].click();
                }
            });

            continueBtn.addEventListener('click', function() {
                const currentIndex = parseInt(continueBtn.dataset.currentIndex);
                const currentPage = continueBtn.dataset.currentPage;

                // Get all tutorials for the current page
                const pageTutorials = document.querySelectorAll(
                    `.tutorial-item[data-page="${currentPage}"]`);

                if (currentIndex < pageTutorials.length - 1) {
                    // Click the next tutorial in this page
                    pageTutorials[currentIndex + 1].click();
                }
            });
        });
    </script>

@endif
