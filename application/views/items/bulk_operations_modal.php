<?php defined('BASEPATH') OR exit(''); ?>

<!-- Bulk Operations Modal -->
<div id="bulkOperationsModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center">Bulk Operations</h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#bulkImport">Import</a></li>
                    <li><a data-toggle="tab" href="#bulkExport">Export</a></li>
                    <li><a data-toggle="tab" href="#bulkPrice">Price Update</a></li>
                    <li><a data-toggle="tab" href="#bulkStock">Stock Adjustment</a></li>
                    <li><a data-toggle="tab" href="#bulkDelete">Delete/Archive</a></li>
                </ul>

                <div class="tab-content" style="margin-top: 20px;">
                    <!-- Import Tab -->
                    <div id="bulkImport" class="tab-pane fade in active">
                        <h4>Import Items from CSV/Excel</h4>
                        <p>Upload a CSV or Excel file with columns: <strong>Name, Code, Quantity, Price, Description</strong></p>
                        <div class="alert alert-info">
                            <strong>Download Template:</strong> 
                            <a href="<?= base_url('public/templates/items_import_template.csv') ?>" download class="btn btn-sm btn-info">
                                <i class="fa fa-download"></i> Download CSV Template
                            </a>
                        </div>
                        <p><small><strong>File Format Requirements:</strong></p>
                        <ul>
                            <li>First row must be header: Name, Code, Quantity, Price, Description</li>
                            <li>Each subsequent row represents one item</li>
                            <li>Code must be unique for each item</li>
                            <li>Quantity and Price must be numeric values</li>
                            <li>Description is optional</li>
                        </ul>
                        </small></p>
                        <form id="bulkImportForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="importFile">Select File</label>
                                <input type="file" id="importFile" name="file" accept=".csv,.xls,.xlsx" class="form-control" required>
                                <small class="help-block">Supported formats: CSV, XLS, XLSX (Max 2MB)</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Import Items</button>
                        </form>
                        <div id="importResults" style="margin-top: 15px;"></div>
                    </div>

                    <!-- Export Tab -->
                    <div id="bulkExport" class="tab-pane fade">
                        <h4>Export Items</h4>
                        <div class="form-group">
                            <label>Export Format</label>
                            <select id="exportFormat" class="form-control">
                                <option value="csv">CSV</option>
                                <option value="excel">Excel</option>
                                <option value="pdf">PDF</option>
                            </select>
                        </div>
                        <button class="btn btn-success" id="exportItemsBtn">Export All Items</button>
                    </div>

                    <!-- Price Update Tab -->
                    <div id="bulkPrice" class="tab-pane fade">
                        <h4>Bulk Price Update</h4>
                        <p>Select items first, then choose update type:</p>
                        <div class="alert alert-info">
                            <strong>Note:</strong> You need to select items from the main table first by checking the boxes.
                        </div>
                        <form id="bulkPriceForm">
                            <div class="form-group">
                                <label>Update Type</label>
                                <select id="priceUpdateType" class="form-control" required>
                                    <option value="">Select...</option>
                                    <option value="percentage">Percentage Increase/Decrease</option>
                                    <option value="fixed">Fixed Amount Add/Subtract</option>
                                    <option value="set">Set Specific Price</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label id="priceValueLabel">Value</label>
                                <input type="number" id="priceValue" class="form-control" step="0.01" required>
                                <small id="priceValueHelp" class="help-block"></small>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Prices</button>
                        </form>
                        <div id="priceUpdateResults" style="margin-top: 15px;"></div>
                    </div>

                    <!-- Stock Adjustment Tab -->
                    <div id="bulkStock" class="tab-pane fade">
                        <h4>Bulk Stock Adjustment</h4>
                        <p>Select items first, then adjust stock:</p>
                        <div class="alert alert-info">
                            <strong>Note:</strong> You need to select items from the main table first.
                        </div>
                        <form id="bulkStockForm">
                            <div class="form-group">
                                <label>Adjustment Type</label>
                                <select id="stockAdjustmentType" class="form-control" required>
                                    <option value="">Select...</option>
                                    <option value="add">Add Stock</option>
                                    <option value="subtract">Subtract Stock</option>
                                    <option value="set">Set Quantity</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" id="stockQuantity" class="form-control" min="0" step="1" required>
                            </div>
                            <div class="form-group">
                                <label>Reason</label>
                                <textarea id="stockReason" class="form-control" rows="2" placeholder="Reason for stock adjustment"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Adjust Stock</button>
                        </form>
                        <div id="stockAdjustResults" style="margin-top: 15px;"></div>
                    </div>

                    <!-- Delete/Archive Tab -->
                    <div id="bulkDelete" class="tab-pane fade">
                        <h4>Bulk Delete/Archive Items</h4>
                        <div class="alert alert-danger">
                            <strong>Warning:</strong> This action cannot be undone. Select items carefully.
                        </div>
                        <form id="bulkDeleteForm">
                            <div class="form-group">
                                <label>Action Type</label>
                                <select id="deleteType" class="form-control" required>
                                    <option value="">Select...</option>
                                    <option value="archive">Archive (Soft Delete)</option>
                                    <option value="delete">Delete Permanently</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="confirmDelete" required>
                                        I understand this action is irreversible
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-danger">Delete/Archive Selected</button>
                        </form>
                        <div id="deleteResults" style="margin-top: 15px;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Price update type change handler
    $('#priceUpdateType').on('change', function() {
        var type = $(this).val();
        var label = $('#priceValueLabel');
        var help = $('#priceValueHelp');
        
        if (type === 'percentage') {
            label.text('Percentage (%)');
            help.text('Enter positive number to increase, negative to decrease (e.g., 10 for 10% increase, -5 for 5% decrease)');
        } else if (type === 'fixed') {
            label.text('Amount');
            help.text('Enter positive number to add, negative to subtract (e.g., 5.50 to add, -2.00 to subtract)');
        } else if (type === 'set') {
            label.text('New Price');
            help.text('Enter the exact price to set for all selected items');
        } else {
            label.text('Value');
            help.text('');
        }
    });
});
</script>

