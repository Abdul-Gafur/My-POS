/**
 * Bulk Operations JavaScript Handler
 */

$(document).ready(function() {
    var selectedItems = [];
    
    // Toggle bulk operations modal
    $('#bulkOperationsBtn').on('click', function() {
        $('#bulkOperationsModal').modal('show');
    });
    
    // Advanced search toggle
    $('#advancedSearchToggle').on('click', function() {
        $('#advancedSearchPanel').toggleClass('hidden');
    });
    
    // Apply filters
    $('#applyFilters').on('click', function() {
        var searchValue = $('#itemSearch').val();
        var priceMin = $('#priceMin').val();
        var priceMax = $('#priceMax').val();
        var stockStatus = $('#stockStatusFilter').val();
        
        // Build search URL with filters
        var searchUrl = appRoot + 'search/itemSearch?v=' + encodeURIComponent(searchValue);
        if (priceMin) searchUrl += '&price_min=' + priceMin;
        if (priceMax) searchUrl += '&price_max=' + priceMax;
        if (stockStatus && stockStatus !== 'all') searchUrl += '&stock_status=' + stockStatus;
        
        $.get(searchUrl, function(data) {
            $('#itemsListTable').html(data.itemsListTable);
        });
    });
    
    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#priceMin, #priceMax').val('');
        $('#stockStatusFilter').val('all');
        $('#itemSearch').val('');
        lilt(); // Reload items list
    });
    
    // Bulk Import
    $('#bulkImportForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: appRoot + 'bulkoperations/importItems',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (result.status === 1) {
                    $('#importResults').html(
                        '<div class="alert alert-success">' +
                        '<strong>Success!</strong> ' + result.msg +
                        '<br>Imported: ' + result.details.success + ', Failed: ' + result.details.failed +
                        '</div>'
                    );
                    lilt(); // Reload items list
                    
                    if (result.details.errors && result.details.errors.length > 0) {
                        var errorsHtml = '<ul>';
                        result.details.errors.slice(0, 10).forEach(function(err) {
                            errorsHtml += '<li>' + err + '</li>';
                        });
                        errorsHtml += '</ul>';
                        $('#importResults').append('<div class="alert alert-warning"><strong>Errors:</strong>' + errorsHtml + '</div>');
                    }
                } else {
                    $('#importResults').html('<div class="alert alert-danger">' + result.msg + '</div>');
                }
            },
            error: function() {
                $('#importResults').html('<div class="alert alert-danger">Error uploading file. Please try again.</div>');
            }
        });
    });
    
    // Bulk Export
    $('#exportItemsBtn').on('click', function() {
        var format = $('#exportFormat').val();
        window.location.href = appRoot + 'bulkoperations/exportItems?format=' + format;
    });
    
    // Bulk Price Update
    $('#bulkPriceForm').on('submit', function(e) {
        e.preventDefault();
        
        var itemIds = getSelectedItems();
        
        if (itemIds.length === 0) {
            $('#priceUpdateResults').html('<div class="alert alert-warning">Please select items first by checking the boxes in the items table.</div>');
            return;
        }
        
        var updateType = $('#priceUpdateType').val();
        var value = $('#priceValue').val();
        
        if (!updateType || !value) {
            $('#priceUpdateResults').html('<div class="alert alert-danger">Please fill all required fields.</div>');
            return;
        }
        
        $.ajax({
            url: appRoot + 'bulkoperations/bulkUpdatePrices',
            type: 'POST',
            data: {
                item_ids: itemIds,
                update_type: updateType,
                value: value
            },
            success: function(response) {
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (result.status === 1) {
                    $('#priceUpdateResults').html('<div class="alert alert-success">' + result.msg + '</div>');
                    lilt(); // Reload items list
                } else {
                    $('#priceUpdateResults').html('<div class="alert alert-danger">' + result.msg + '</div>');
                }
            }
        });
    });
    
    // Bulk Stock Adjustment
    $('#bulkStockForm').on('submit', function(e) {
        e.preventDefault();
        
        var itemIds = getSelectedItems();
        
        if (itemIds.length === 0) {
            $('#stockAdjustResults').html('<div class="alert alert-warning">Please select items first.</div>');
            return;
        }
        
        var adjustmentType = $('#stockAdjustmentType').val();
        var quantity = $('#stockQuantity').val();
        var reason = $('#stockReason').val() || 'Bulk stock adjustment';
        
        $.ajax({
            url: appRoot + 'bulkoperations/bulkStockAdjustment',
            type: 'POST',
            data: {
                item_ids: itemIds,
                adjustment_type: adjustmentType,
                quantity: quantity,
                reason: reason
            },
            success: function(response) {
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (result.status === 1) {
                    $('#stockAdjustResults').html('<div class="alert alert-success">' + result.msg + '</div>');
                    lilt(); // Reload items list
                } else {
                    $('#stockAdjustResults').html('<div class="alert alert-danger">' + result.msg + '</div>');
                }
            }
        });
    });
    
    // Bulk Delete/Archive
    $('#bulkDeleteForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!$('#confirmDelete').is(':checked')) {
            $('#deleteResults').html('<div class="alert alert-danger">Please confirm the action.</div>');
            return;
        }
        
        var itemIds = getSelectedItems();
        
        if (itemIds.length === 0) {
            $('#deleteResults').html('<div class="alert alert-warning">Please select items first.</div>');
            return;
        }
        
        var deleteType = $('#deleteType').val();
        
        if (!confirm('Are you sure you want to ' + deleteType + ' ' + itemIds.length + ' item(s)? This action cannot be undone.')) {
            return;
        }
        
        $.ajax({
            url: appRoot + 'bulkoperations/bulkDelete',
            type: 'POST',
            data: {
                item_ids: itemIds,
                delete_type: deleteType
            },
            success: function(response) {
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (result.status === 1) {
                    $('#deleteResults').html('<div class="alert alert-success">' + result.msg + '</div>');
                    lilt(); // Reload items list
                    $('#bulkOperationsModal').modal('hide');
                } else {
                    $('#deleteResults').html('<div class="alert alert-danger">' + result.msg + '</div>');
                }
            }
        });
    });
    
    // Helper function to get selected items
    function getSelectedItems() {
        var selected = [];
        $('input[type="checkbox"].item-checkbox:checked').each(function() {
            selected.push($(this).data('item-id'));
        });
        return selected;
    }
    
    // Select all functionality
    $(document).on('change', '#selectAllItems', function() {
        var isChecked = $(this).is(':checked');
        $('.item-checkbox').prop('checked', isChecked);
        selectedItems = getSelectedItems();
    });
    
    // Listen for checkbox changes
    $(document).on('change', '.item-checkbox', function() {
        // Update selected items array
        selectedItems = getSelectedItems();
        
        // Update select all checkbox state
        var totalCheckboxes = $('.item-checkbox').length;
        var checkedCheckboxes = $('.item-checkbox:checked').length;
        $('#selectAllItems').prop('checked', totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0);
    });
});

