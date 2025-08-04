# Enhanced Corporation Search System

## Overview

The Enhanced Corporation Search System provides advanced search capabilities specifically designed for finding corporation records in both IIBS and OFAC databases. This system includes sophisticated matching algorithms, real-time suggestions, and relevance scoring to deliver the most accurate search results.

## Key Features

### 🔍 Advanced Search Algorithms

1. **Exact Match Search**
   - Highest priority for exact corporation name matches
   - Perfect for known corporation names

2. **Partial Match Search**
   - Finds corporations containing search terms
   - Useful for incomplete or partial names

3. **Phonetic Search (SOUNDEX)**
   - Matches similar-sounding corporation names
   - Helps find corporations with spelling variations

4. **Word Boundary Search**
   - Matches whole words within corporation names
   - Reduces false positives from partial word matches

5. **Alternate Name Search**
   - Searches through all alternate names and aliases
   - Comprehensive coverage of all known corporation identities

### 📊 Relevance Scoring System

The system calculates relevance scores based on:
- **Exact Match (100 points)**: Perfect corporation name match
- **Starts With (80 points)**: Corporation name starts with search term
- **Contains (60 points)**: Corporation name contains search term
- **Alternate Match (50 points)**: Match in alternate names
- **Word Boundary (40 points)**: Whole word matches
- **Recent Records (10 points)**: Bonus for recently added records

### 🚀 Real-Time Features

1. **Auto-Suggestions**
   - Real-time search suggestions as you type
   - Displays both IIBS and OFAC sources
   - Keyboard navigation support (Arrow keys, Enter, Escape)

2. **Search Highlighting**
   - Highlights matching terms in search results
   - Visual emphasis on relevant content

3. **Category Filtering**
   - Filter by IIBS only, OFAC only, or all sources
   - Streamlined results for specific databases

### 📈 Statistics Dashboard

- **IIBS Corporation Count**: Total corporations in IIBS database
- **OFAC Corporation Count**: Total corporations in OFAC database
- **Total Corporations**: Combined count across both databases
- **Recent Additions**: Corporations added in the last 30 days

## File Structure

```
iibs-ofac-modal-system-main/
├── enhanced_search.php          # Core enhanced search functions
├── search_corporations.php      # Main corporation search interface
├── get_suggestions.php          # AJAX endpoint for search suggestions
├── admin_dashboard.php          # Updated admin dashboard with enhanced search
├── index.php                    # Updated main page with enhanced search link
└── ENHANCED_SEARCH_README.md    # This documentation file
```

## Enhanced Search Functions

### `enhancedCorporationSearch($query, $category = 'all')`
- **Purpose**: Main search function with advanced matching
- **Parameters**: 
  - `$query`: Search term
  - `$category`: 'all', 'iibs', or 'ofac'
- **Returns**: Array of matching corporation records with relevance scoring

### `getCorporationSuggestions($query, $limit = 10)`
- **Purpose**: Get search suggestions for autocomplete
- **Parameters**: 
  - `$query`: Partial search term
  - `$limit`: Maximum number of suggestions
- **Returns**: Array of suggested corporation names

### `getCorporationStats()`
- **Purpose**: Get statistics about corporation records
- **Returns**: Array with counts for IIBS, OFAC, total, and recent additions

### `calculateRelevanceScore($record, $query)`
- **Purpose**: Calculate relevance score for search results
- **Parameters**: 
  - `$record`: Corporation record
  - `$query`: Search term
- **Returns**: Numerical relevance score

### `highlightSearchTerms($text, $searchTerm)`
- **Purpose**: Highlight search terms in result text
- **Parameters**: 
  - `$text`: Text to highlight
  - `$searchTerm`: Term to highlight
- **Returns**: HTML with highlighted terms

## Usage Examples

### Basic Corporation Search
```php
// Search for all corporations containing "ABC"
$results = enhancedCorporationSearch("ABC");
```

### Category-Specific Search
```php
// Search only OFAC corporations
$results = enhancedCorporationSearch("Trading Corp", "ofac");
```

### Get Search Suggestions
```php
// Get suggestions for autocomplete
$suggestions = getCorporationSuggestions("Corp", 5);
```

### Get Statistics
```php
// Get corporation statistics
$stats = getCorporationStats();
echo "Total corporations: " . $stats['total_corporations'];
```

## Search Interface Features

### Advanced Search Form
- **Corporation Name Input**: Main search field with autocomplete
- **Category Filter**: Dropdown to filter by IIBS/OFAC
- **Real-time Suggestions**: Dropdown with matching corporation names
- **Search Tips**: Contextual help for users

### Results Display
- **Relevance Scoring**: Results sorted by relevance score
- **Source Identification**: Clear IIBS/OFAC badges
- **Highlighted Terms**: Search terms highlighted in results
- **Alternate Names**: Display of all known aliases
- **Detailed Information**: Source media, dates, status, and descriptions

### Interactive Features
- **Keyboard Navigation**: Arrow keys to navigate suggestions
- **Click to Select**: Click suggestions to auto-fill search
- **Popular Searches**: Display of frequently searched corporations
- **Clear Search**: Easy way to reset search parameters

## Performance Optimizations

1. **Database Indexing**
   - Indexes on corporation names and alternate names
   - Optimized for fast search queries

2. **AJAX Suggestions**
   - Debounced input to reduce server requests
   - Cached suggestions for better performance

3. **Relevance Sorting**
   - Efficient scoring algorithm
   - Results sorted by relevance for better user experience

## Security Features

1. **Input Sanitization**
   - All user inputs sanitized using `sanitize_input()`
   - Protection against XSS attacks

2. **SQL Injection Prevention**
   - Prepared statements for all database queries
   - Parameterized queries with proper binding

3. **Session Management**
   - Secure session handling for admin features
   - Proper authentication checks

## Browser Compatibility

- **Modern Browsers**: Chrome, Firefox, Safari, Edge
- **JavaScript Features**: ES6+ features used
- **Responsive Design**: Works on desktop and mobile devices
- **Accessibility**: Keyboard navigation and screen reader support

## API Endpoints

### `/get_suggestions.php`
- **Method**: GET
- **Parameters**: `query` (string, min 2 characters)
- **Response**: JSON array of suggestions
- **Example**: `/get_suggestions.php?query=corp`

## Customization Options

### Search Algorithm Tuning
Modify relevance scoring weights in `calculateRelevanceScore()`:
```php
// Adjust scoring weights
$exactMatchScore = 100;    // Exact match weight
$startsWithScore = 80;     // Starts with weight
$containsScore = 60;       // Contains weight
```

### Suggestion Limits
Adjust suggestion limits in search interface:
```php
// Change number of suggestions
$suggestions = getCorporationSuggestions($query, 15); // Show 15 instead of 10
```

### Search Timeout
Modify AJAX timeout in JavaScript:
```javascript
// Adjust suggestion delay
searchTimeout = setTimeout(() => {
    fetchSuggestions(query);
}, 500); // 500ms instead of 300ms
```

## Troubleshooting

### Common Issues

1. **No Suggestions Appearing**
   - Check if `get_suggestions.php` is accessible
   - Verify database connection in `config.php`
   - Check browser console for JavaScript errors

2. **Slow Search Performance**
   - Ensure database indexes are created
   - Check server resources and database optimization
   - Consider caching for frequently searched terms

3. **Relevance Scores Not Working**
   - Verify `calculateRelevanceScore()` function
   - Check if search terms are being passed correctly
   - Review scoring algorithm logic

### Debug Mode
Enable debug mode by adding to `enhanced_search.php`:
```php
// Add at top of file
define('DEBUG_MODE', true);

// Add debug logging
if (DEBUG_MODE) {
    error_log("Search query: " . $query);
    error_log("Results count: " . count($results));
}
```

## Future Enhancements

1. **Machine Learning Integration**
   - AI-powered search suggestions
   - Learning from user search patterns

2. **Advanced Filtering**
   - Date range filters
   - Status-based filtering
   - Geographic filtering

3. **Export Functionality**
   - Export search results to CSV/PDF
   - Batch operations on search results

4. **Search Analytics**
   - Track popular search terms
   - User behavior analytics
   - Search performance metrics

## Support

For technical support or feature requests, please refer to the main system documentation or contact the system administrator.

---

**Last Updated**: December 2024  
**Version**: 1.0  
**Compatibility**: PHP 7.4+, MySQL 5.7+, Modern Browsers
