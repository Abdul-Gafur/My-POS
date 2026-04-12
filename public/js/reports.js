/**
 * Reports JavaScript Handler
 */

$(document).ready(function() {
    // Generate Sales Report
    $('#generateSalesReport').on('click', function() {
        var dateFrom = $('#salesDateFrom').val();
        var dateTo = $('#salesDateTo').val();
        
        $('#salesReportContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Generating report...</div>');
        
        $.get(appRoot + 'reports/sales', {
            date_from: dateFrom,
            date_to: dateTo
        }, function(data) {
            $('#salesReportContent').html(data);
        }).fail(function() {
            $('#salesReportContent').html('<div class="alert alert-danger">Error generating report</div>');
        });
    });
    
    // Export Sales Report
    $('#exportSalesReport').on('click', function() {
        var dateFrom = $('#salesDateFrom').val();
        var dateTo = $('#salesDateTo').val();
        window.location.href = appRoot + 'reports/exportSales?date_from=' + dateFrom + '&date_to=' + dateTo;
    });
    
    // Generate Product Report
    $('#generateProductReport').on('click', function() {
        $('#productReportContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Generating report...</div>');
        
        $.get(appRoot + 'reports/products', function(data) {
            $('#productReportContent').html(data);
        }).fail(function() {
            $('#productReportContent').html('<div class="alert alert-danger">Error generating report</div>');
        });
    });
    
    // Load chart data for analytics
    loadChartData();
    
    function loadChartData() {
        // Daily Sales Chart
        $.get(appRoot + 'reports/getChartData', {type: 'daily'}, function(data) {
            var labels = [];
            var sales = [];
            
            if(data && data.length > 0) {
                data.forEach(function(item) {
                    labels.push(item.date);
                    sales.push(item.sales);
                });
                
                var ctx = document.getElementById('dailySalesChart');
                if(ctx) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Daily Sales (GH₵)',
                                data: sales,
                                borderColor: 'rgb(75, 192, 192)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                tension: 0.1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true
                        }
                    });
                }
            }
        });
        
        // Top Products Chart
        $.get(appRoot + 'reports/getChartData', {type: 'top_products'}, function(data) {
            var labels = [];
            var sold = [];
            
            if(data && data.length > 0) {
                data.forEach(function(item) {
                    labels.push(item.name);
                    sold.push(item.sold);
                });
                
                var ctx = document.getElementById('topProductsChart');
                if(ctx) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Units Sold',
                                data: sold,
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            }
        });
    }
});

