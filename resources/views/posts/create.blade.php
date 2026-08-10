<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-950 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Navigation -->
            <div class="mb-8">
                <a href="{{ route('posts.index') }}" 
                   class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Posts
                </a>
            </div>

            <!-- Hero Header -->
            <div class="mb-10 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 mb-6">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                    Craft Your
                    <span class="bg-gradient-to-r from-green-500 to-emerald-500 bg-clip-text text-transparent">Story</span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Share your thoughts, ideas, and experiences with our community
                </p>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="p-6 md:p-8" id="createPostForm">
                    @csrf

                    <!-- Title Section -->
                    <div class="mb-10">
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Post Title
                            </label>
                            <span class="text-xs text-gray-500 dark:text-gray-400" id="titleCounter">0/120</span>
                        </div>
                        <div class="relative">
                            <input type="text" 
                                   name="title" 
                                   id="title"
                                   maxlength="120"
                                   required
                                   placeholder="What's on your mind? Give it a catchy title..."
                                   class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-500/10 transition-all duration-200"
                                   oninput="updateTitleCounter()">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Make it descriptive and attention-grabbing
                        </p>
                    </div>

                    <!-- Content Section -->
                    <div class="mb-10">
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Your Story
                            </label>
                            <span class="text-xs text-gray-500 dark:text-gray-400" id="contentCounter">0/5000</span>
                        </div>
                        <div class="relative">
                            <textarea 
                                name="content" 
                                id="content"
                                rows="10"
                                maxlength="5000"
                                required
                                placeholder="Share your thoughts, experiences, or expertise. What do you want to tell the world?"
                                class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-base text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:border-brand-900 focus:ring-4 focus:ring-brand-900/10 transition-all duration-200 resize-none"
                                oninput="updateContentCounter()"></textarea>
                            @error('content')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Be authentic and engaging. Your story matters!
                        </p>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="mb-10">
                        <label class="block text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Post Image (Optional)
                        </label>
                        <div class="relative">
                            <input 
                                type="file" 
                                name="image" 
                                id="image"
                                accept="image/*"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-brand-900/30 dark:file:text-brand-400 focus:outline-none focus:border-brand-900 focus:ring-4 focus:ring-brand-900/10 transition-all duration-200"
                            >
                            @error('image')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Max size: 2MB. Supported formats: JPEG, PNG, JPG, GIF, WebP
                        </p>

                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-4 hidden">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview:</p>
                            <div class="relative inline-block">
                                <img src="" alt="Preview" class="max-w-md w-full rounded-lg shadow-lg border-2 border-gray-200 dark:border-gray-600">
                                <button type="button" 
                                        onclick="clearImagePreview()"
                                        class="absolute top-2 right-2 p-2 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Category Selection (Optional Enhancement) -->
                    <div class="mb-10">
                        <label class="block text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Category (Optional)
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @php
                                $categories = ['Technology', 'Lifestyle', 'Education', 'Business', 'Health', 'Entertainment', 'Travel', 'Other'];
                            @endphp
                            @foreach($categories as $category)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="category" value="{{ $category }}" class="sr-only peer">
                                    <div class="px-4 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-center font-medium transition-all duration-200 peer-checked:bg-gradient-to-r peer-checked:from-brand-900 peer-checked:to-brand-800 peer-checked:text-white peer-checked:shadow-lg">
                                        {{ $category }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <!-- Draft Option -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="saveDraft" 
                                   name="draft"
                                   class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                            <label for="saveDraft" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Save as draft
                            </label>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                            <button type="button" 
                                    onclick="window.location.href='{{ route('posts.index') }}'"
                                    class="px-8 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-xl font-semibold transition-colors duration-200">
                                Cancel
                            </button>
                            <button type="submit"
                                    id="submitBtn"
                                    class="group relative px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl font-bold text-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 overflow-hidden">
                                <!-- Animated Background -->
                                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-green-600 to-emerald-700 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
                                
                                <!-- Button Content -->
                                <span class="relative flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <span id="submitText">Publish Post</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Writing Tips -->
            <div class="mt-8 bg-gradient-to-r from-brand-50 to-brand-50 dark:from-brand-900/20 dark:to-brand-900/20 border border-brand-100 dark:border-brand-800 rounded-2xl p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-brand-500 dark:text-brand-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Writing Tips</h3>
                        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Start with a compelling headline</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Use clear paragraphs and headings</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Add value and be authentic</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Proofread before publishing</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Character Counters -->
    <script>
        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB max)
                if (file.size > 2048 * 1024) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Please upload a valid image file (JPEG, PNG, JPG, GIF, or WebP)');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    const img = preview.querySelector('img');
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        function clearImagePreview() {
            const preview = document.getElementById('imagePreview');
            const imageInput = document.getElementById('image');
            preview.classList.add('hidden');
            imageInput.value = '';
        }

        function updateTitleCounter() {
            const titleInput = document.getElementById('title');
            const counter = document.getElementById('titleCounter');
            const length = titleInput.value.length;
            const maxLength = 120;
            
            counter.textContent = `${length}/${maxLength}`;
            
            if (length > maxLength * 0.8) {
                counter.classList.remove('text-gray-500', 'dark:text-gray-400');
                counter.classList.add('text-yellow-500');
            } else {
                counter.classList.remove('text-yellow-500');
                counter.classList.add('text-gray-500', 'dark:text-gray-400');
            }
        }

        function updateContentCounter() {
            const contentInput = document.getElementById('content');
            const counter = document.getElementById('contentCounter');
            const length = contentInput.value.length;
            const maxLength = 5000;
            
            counter.textContent = `${length}/${maxLength}`;
            
            if (length > maxLength * 0.9) {
                counter.classList.remove('text-gray-500', 'dark:text-gray-400');
                counter.classList.add('text-red-500');
            } else if (length > maxLength * 0.75) {
                counter.classList.remove('text-gray-500', 'dark:text-gray-400', 'text-red-500');
                counter.classList.add('text-yellow-500');
            } else {
                counter.classList.remove('text-yellow-500', 'text-red-500');
                counter.classList.add('text-gray-500', 'dark:text-gray-400');
            }
        }

        // Initialize counters
        document.addEventListener('DOMContentLoaded', function() {
            updateTitleCounter();
            updateContentCounter();

            // Handle draft checkbox
            const draftCheckbox = document.getElementById('saveDraft');
            const submitText = document.getElementById('submitText');
            const submitBtn = document.getElementById('submitBtn');
            
            draftCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    submitText.textContent = 'Save as Draft';
                    submitBtn.classList.remove('from-green-500', 'to-emerald-600', 'hover:from-green-600', 'hover:to-emerald-700');
                    submitBtn.classList.add('from-gray-500', 'to-gray-600', 'hover:from-gray-600', 'hover:to-gray-700');
                } else {
                    submitText.textContent = 'Publish Post';
                    submitBtn.classList.remove('from-gray-500', 'to-gray-600', 'hover:from-gray-600', 'hover:to-gray-700');
                    submitBtn.classList.add('from-green-500', 'to-emerald-600', 'hover:from-green-600', 'hover:to-emerald-700');
                }
            });

            // Form validation
            const form = document.getElementById('createPostForm');
            form.addEventListener('submit', function(e) {
                const title = document.getElementById('title').value.trim();
                const content = document.getElementById('content').value.trim();
                
                if (!title || !content) {
                    e.preventDefault();
                    alert('Please fill in all required fields');
                    return false;
                }
                
                // Show loading state
                const btn = document.getElementById('submitBtn');
                const originalText = btn.innerHTML;
                btn.innerHTML = `
                    <span class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                `;
                btn.disabled = true;
            });
        });
    </script>

    <style>
        /* Custom scrollbar for textarea */
        textarea {
            scrollbar-width: thin;
            scrollbar-color: #CBD5E0 transparent;
        }
        
        textarea::-webkit-scrollbar {
            width: 8px;
        }
        
        textarea::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 4px;
        }
        
        textarea::-webkit-scrollbar-thumb {
            background-color: #CBD5E0;
            border-radius: 4px;
        }
        
        textarea.dark::-webkit-scrollbar-thumb {
            background-color: #4B5563;
        }
        
        /* Focus styles */
        input:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        .dark input:focus, .dark textarea:focus {
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }
        
        /* Smooth transitions */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }
    </style>
</x-app-layout>