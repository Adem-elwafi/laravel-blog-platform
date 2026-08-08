import React, { useState, useEffect, useRef } from 'react';

export default function PostFilters({ authors = [], initialSearch = '', initialAuthor = '', initialSort = 'newest' }) {
  const [search, setSearch] = useState(initialSearch);
  const [author, setAuthor] = useState(initialAuthor);
  const [sort, setSort] = useState(initialSort);
  const searchTimerRef = useRef(null);

  // Debounce search input: only apply filters 500ms after user stops typing
  // This prevents excessive page reloads while user is actively typing
  useEffect(() => {
    // Skip if search hasn't changed from initial value (prevents unnecessary navigation on mount)
    if (search === initialSearch) return;

    // Clear any existing timer
    if (searchTimerRef.current) {
      clearTimeout(searchTimerRef.current);
    }

    // Set new timer to apply search filter
    searchTimerRef.current = setTimeout(() => {
      applyFilters(search, author, sort);
    }, 500);

    // Cleanup timer on unmount or when search changes again
    return () => {
      if (searchTimerRef.current) {
        clearTimeout(searchTimerRef.current);
      }
    };
  }, [search]);

  // Build URL with search parameters and navigate to new filtered view
  // Uses URLSearchParams to properly encode parameters
  // Only includes non-empty filters in URL to keep it clean
  const applyFilters = (searchTerm, authorId, sortBy) => {
    const params = new URLSearchParams();
    
    if (searchTerm && searchTerm.trim()) params.append('search', searchTerm.trim());
    if (authorId) params.append('author', authorId);
    if (sortBy && sortBy !== 'newest') params.append('sort', sortBy); // Only add sort if not default

    const queryString = params.toString();
    const newUrl = queryString ? `/posts?${queryString}` : '/posts';
    
    // Navigate to new URL (causes page reload with filtered results from backend)
    window.location.href = newUrl;
  };

  // Handle explicit Apply button click (for author and sort changes)
  const handleApplyFilters = () => {
    applyFilters(search, author, sort);
  };

  // Check if any filters are active (other than default sort)
  const hasActiveFilters = search || author || (sort && sort !== 'newest');

  // Clear all filters and return to /posts
  const clearFilters = () => {
    window.location.href = '/posts';
  };

  return (
    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        {/* Search Input - Debounced for better performance */}
        <div>
          <label htmlFor="search" className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Search Posts
          </label>
          <input
            id="search"
            type="text"
            placeholder="Search by title or content..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors duration-200"
          />
        </div>

        {/* Author Filter Dropdown */}
        <div>
          <label htmlFor="author" className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Filter by Author
          </label>
          <select
            id="author"
            value={author}
            onChange={(e) => setAuthor(e.target.value)}
            className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors duration-200"
          >
            <option value="">All Authors</option>
            {authors.map((auth) => (
              <option key={auth.id} value={auth.id}>
                {auth.name}
              </option>
            ))}
          </select>
        </div>

        {/* Sort Options Dropdown */}
        <div>
          <label htmlFor="sort" className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Sort By
          </label>
          <select
            id="sort"
            value={sort}
            onChange={(e) => setSort(e.target.value)}
            className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors duration-200"
          >
            <option value="newest">Newest First</option>
            <option value="popular">Most Popular (Likes)</option>
            <option value="most_commented">Most Commented</option>
          </select>
        </div>
      </div>

      {/* Action Buttons */}
      <div className="mt-4 flex gap-3">
        {/* Apply Filters Button - Apply author and sort changes */}
        <button
          onClick={handleApplyFilters}
          className="px-6 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors duration-200 font-medium"
        >
          Apply Filters
        </button>

        {/* Clear Filters Button - Only displayed when filters are active */}
        {hasActiveFilters && (
          <button
            onClick={clearFilters}
            className="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200 font-medium"
          >
            Clear Filters
          </button>
        )}
      </div>
    </div>
  );
}
