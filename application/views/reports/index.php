<?php defined('BASEPATH') OR exit(''); ?>

<div class="row hidden-print">
    <div class="col-sm-12">
        <!-- Summary Cards -->
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-sm-3">
                <div class="panel panel-primary">
                    <div class="panel-body" style="background-color: #337ab7; color: white; height: 100px;">
                        <div class="pull-left"><i class="fa fa-money fa-3x"></i></div>
                        <div class="pull-right text-right">
                            <div style="font-size: 28px; font-weight: bold;">GH₵<?= number_format($totalSales, 2) ?></div>
                            <div>Today's Sales</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="panel panel-success">
                    <div class="panel-body" style="background-color: #5cb85c; color: white; height: 100px;">
                        <div class="pull-left"><i class="fa fa-exchange fa-3x"></i></div>
                        <div class="pull-right text-right">
                            <div style="font-size: 28px; font-weight: bold;"><?= number_format($totalTransactions) ?></div>
                            <div>Total Transactions</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="panel panel-info">
                    <div class="panel-body" style="background-color: #5bc0de; color: white; height: 100px;">
                        <div class="pull-left"><i class="fa fa-archive fa-3x"></i></div>
                        <div class="pull-right text-right">
                            <div style="font-size: 28px; font-weight: bold;"><?= number_format($totalItems) ?></div>
                            <div>Total Items</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="panel panel-warning">
                    <div class="panel-body" style="background-color: #f0ad4e; color: white; height: 100px;">
                        <div class="pull-left"><i class="fa fa-line-chart fa-3x"></i></div>
                        <div class="pull-right text-right">
                            <div style="font-size: 28px; font-weight: bold;">
                                <?php 
                                $query = $this->db->query('SELECT SUM(totalPrice) as total FROM transactions')->row(); 
                                echo number_format(floatval($query->total), 2); 
                                ?>
                            </div>
                            <div>Total Revenue</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Tabs -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#salesReport" aria-controls="salesReport" role="tab" data-toggle="tab">
                            <i class="fa fa-shopping-cart"></i> Sales Report
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#productReport" aria-controls="productReport" role="tab" data-toggle="tab">
                            <i class="fa fa-cubes"></i> Product Performance
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#analyticsReport" aria-controls="analyticsReport" role="tab" data-toggle="tab">
                            <i class="fa fa-bar-chart"></i> Analytics
                        </a>
                    </li>
                </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <!-- Sales Report Tab -->
                    <div role="tabpanel" class="tab-pane active" id="salesReport">
                        <div class="row">
                            <div class="col-sm-12">
                                <form class="form-inline" style="margin-bottom: 15px;">
                                    <div class="form-group">
                                        <label>Date From:</label>
                                        <input type="date" id="salesDateFrom" class="form-control" value="<?= date('Y-m-01') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Date To:</label>
                                        <input type="date" id="salesDateTo" class="form-control" value="<?= date('Y-m-d') ?>">
                                    </div>
                                    <button type="button" id="generateSalesReport" class="btn btn-primary">
                                        <i class="fa fa-refresh"></i> Generate Report
                                    </button>
                                    <?php if($this->permissions->hasPermission('reports', 'export')): ?>
                                    <button type="button" id="exportSalesReport" class="btn btn-success">
                                        <i class="fa fa-download"></i> Export CSV
                                    </button>
                                    <?php endif; ?>
                                </form>
                                <div id="salesReportContent">
                                    <p class="text-center text-muted">Click "Generate Report" to view sales data</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Performance Tab -->
                    <div role="tabpanel" class="tab-pane" id="productReport">
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" id="generateProductReport" class="btn btn-primary" style="margin-bottom: 15px;">
                                    <i class="fa fa-refresh"></i> Generate Product Report
                                </button>
                                <div id="productReportContent">
                                    <p class="text-center text-muted">Click "Generate Product Report" to view product performance</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Analytics Tab -->
                    <div role="tabpanel" class="tab-pane" id="analyticsReport">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4><i class="fa fa-line-chart"></i> Daily Sales (Last 7 Days)</h4>
                                    </div>
                                    <div class="panel-body">
                                        <canvas id="dailySalesChart" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4><i class="fa fa-bar-chart"></i> Top 5 Products</h4>
                                    </div>
                                    <div class="panel-body">
                                        <canvas id="topProductsChart" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if(isset($topProducts) && $topProducts): ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><h4>Top Selling Products</h4></div>
                                    <div class="panel-body">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Units Sold</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($topProducts as $product): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($product->name) ?></td>
                                                    <td><?= number_format($product->totSold) ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>public/js/chart.js"></script>
<script src="<?= base_url() ?>public/js/reports.js"></script>

