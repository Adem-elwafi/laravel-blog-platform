<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('posts.index') }}" 
                   class="text-brand-600 dark:text-brand-400 hover:text-brand-800 dark:hover:text-brand-300 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Post</h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400">Update your post content and title below</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data" class="p-6 sm:p-8">
                @csrf
                @method('PUT')

                <!-- Title Input -->
                <div class="mb-8">
                    <label for="title" class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">
                        Post Title
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $post->title) }}"
                               required
                               placeholder="Enter a descriptive title"
                               class="pl-10 w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-3 focus:ring-brand-900 focus:border-brand-900 transition-all duration-200 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                    </div>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Image Upload Section -->
                <div class="mb-8">
                    <label for="image" class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">
                        Post Image
                    </label>

                    @if($post->image)
                        <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 font-medium">Current Image:</p>
                            <div class="relative inline-block">
                                <img src="{{ asset('storage/' . $post->image) }}" 
                                     alt="Current post image" 
                                     class="max-w-md w-full rounded-lg shadow-md border-2 border-gray-200 dark:border-gray-600">
                            </div>
                        </div>
                    @endif

                    <div class="relative">
                        <input 
                            type="file" 
                            name="image" 
                            id="image"
                            accept="image/*"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 dark:file:bg-brand-900/30 dark:file:text-brand-400 focus:outline-none focus:border-brand-900 focus:ring-4 focus:ring-brand-900/10 transition-all duration-200"
                        >
                        @error('image')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        @if($post->image)
                            Upload a new image to replace the current one (Max: 2MB)
                        @else
                            Upload an image for this post (Optional, Max: 2MB)
                        @endif
                    </p>

                    <!-- New Image Preview -->
                    <div id="imagePreview" class="mt-4 hidden">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Image Preview:</p>
                        <div class="relative inline-block">
                            <img src="" alt="Preview" class="max-w-md w-full rounded-lg shadow-lg border-2 border-brand-300 dark:border-brand-900">
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

                <!-- Content Input -->
                <div class="mb-10">
                    <label for="content" class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">
                        Content
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute top-4 left-3 flex items-start pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <textarea id="content" 
                                  name="content" 
                                  rows="8"
                                  required
                                  placeholder="Write your post content here..."
                                  class="pl-10 w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-3 focus:ring-brand-900 focus:border-brand-900 transition-all duration-200 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 resize-none">{{ old('content', $post->content) }}</textarea>
                    </div>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Last updated: {{ $post->updated_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <a href="{{ route('posts.show', $post) }}"
                           class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                            Cancel
                        </a>
                        <button type="submit"
                                class="group relative px-8 py-3 bg-gradient-to-r from-brand-900 to-brand-800 hover:from-brand-800 hover:to-brand-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-brand-900/20">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Post
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 p-6 bg-brand-50 dark:bg-brand-900/20 border border-brand-200 dark:border-brand-800 rounded-2xl">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-brand-600 dark:text-brand-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-brand-800 dark:text-brand-300 mb-1">Editing Tips</h3>
                    <p class="text-sm text-brand-700 dark:text-brand-400">
                        Make sure your title is clear and descriptive. Your content should be well-structured and engaging.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add some custom styles for better input focus states -->
    <style>
        textarea {
            scrollbar-width: thin;
            scrollbar-color: #CBD5E0 transparent;
        }
        
        textarea::-webkit-scrollbar {
            width: 6px;
        }
        
        textarea::-webkit-scrollbar-track {
            background: transparent;
        }
        
        textarea::-webkit-scrollbar-thumb {
            background-color: #CBD5E0;
            border-radius: 3px;
        }
        
        textarea.dark::-webkit-scrollbar-thumb {
            background-color: #4B5563;
        }
        
        input:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .dark input:focus, .dark textarea:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
    </style>

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
    </script>
</x-app-layout>