<?php defined('BASEPATH') OR exit(''); ?>

<div class="row">
    <div class="col-sm-12">
        <h3>Product Performance Report</h3>
        
        <?php if(isset($products) && $products): ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>All Products Performance</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Code</th>
                                <th>Stock Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Sold</th>
                                <th>Total Revenue</th>
                                <th>Performance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $product): ?>
                            <?php 
                            $performance = 0;
                            if ($product->totalEarned > 0) {
                                $performance = ($product->totalSold / ($product->totalSold + $product->quantity)) * 100;
                            }
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($product->name) ?></td>
                                <td><?= htmlspecialchars($product->code) ?></td>
                                <td><?= number_format($product->quantity) ?></td>
                                <td>GH₵<?= number_format($product->unitPrice, 2) ?></td>
                                <td><?= number_format($product->totalSold ?: 0) ?></td>
                                <td>GH₵<?= number_format($product->totalEarned ?: 0, 2) ?></td>
                                <td>
                                    <?php if($performance > 50): ?>
                                        <span class="label label-success">High</span>
                                    <?php elseif($performance > 25): ?>
                                        <span class="label label-warning">Medium</span>
                                    <?php else: ?>
                                        <span class="label label-default">Low</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-info">
            No products found.
        </div>
        <?php endif; ?>
    </div>
</div>

