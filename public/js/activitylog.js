/**
 * Activity Log JavaScript Handler
 */

$(document).ready(function() {
    // Load logs on page load
    loadActivityLogs();
    
    // Apply filters
    $('#applyFilters').on('click', function() {
        loadActivityLogs();
    });
    
    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#eventTypeFilter, #staffFilter, #tableFilter, #dateFromFilter, #dateToFilter').val('');
        loadActivityLogs();
    });
    
    // Export logs
    $('#exportLogs').on('click', function() {
        var params = buildFilterParams();
        window.location.href = appRoot + 'activitylog/export?' + params;
    });
    
    // Pagination
    $(document).on('click', '.log-pagination', function(e) {
        e.preventDefault();
        loadActivityLogs($(this).attr('href'));
        return false;
    });
    
    function loadActivityLogs(url) {
        var params = buildFilterParams();
        var loadUrl = url || (appRoot + 'activitylog/loadLogs');
        
        if (params) {
            loadUrl += (url ? '&' : '?') + params;
        }
        
        $('#activityLogTable').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</div>');
        
        $.get(loadUrl, function(data) {
            var result = typeof data === 'string' ? JSON.parse(data) : data;
            $('#activityLogTable').html(result.logTable);
        }).fail(function() {
            $('#activityLogTable').html('<div class="alert alert-danger">Error loading activity logs</div>');
        });
    }
    
    function buildFilterParams() {
        var params = [];
        
        var eventType = $('#eventTypeFilter').val();
        if (eventType) params.push('event_type=' + encodeURIComponent(eventType));
        
        var staffId = $('#staffFilter').val();
        if (staffId) params.push('staff_id=' + encodeURIComponent(staffId));
        
        var tableName = $('#tableFilter').val();
        if (tableName) params.push('table_name=' + encodeURIComponent(tableName));
        
        var dateFrom = $('#dateFromFilter').val();
        if (dateFrom) params.push('date_from=' + encodeURIComponent(dateFrom));
        
        var dateTo = $('#dateToFilter').val();
        if (dateTo) params.push('date_to=' + encodeURIComponent(dateTo));
        
        return params.join('&');
    }
});

