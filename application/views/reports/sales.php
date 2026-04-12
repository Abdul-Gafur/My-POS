<?php defined('BASEPATH') OR exit(''); ?>

<div class="row">
    <div class="col-sm-12">
        <h3>Sales Report: <?= date('M d, Y', strtotime($date_from)) ?> - <?= date('M d, Y', strtotime($date_to)) ?></h3>
        
        <!-- Summary -->
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4>Summary</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-4">
                        <strong>Total Sales:</strong> GH₵<?= number_format($totalSales, 2) ?>
                    </div>
                    <div class="col-sm-4">
                        <strong>Total Items Sold:</strong> <?= number_format($totalQuantity) ?>
                    </div>
                    <div class="col-sm-4">
                        <strong>Number of Transactions:</strong> <?= isset($transactions) ? count($transactions) : 0 ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Transactions Table -->
        <?php if(isset($transactions) && $transactions): ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Transaction Details</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference</th>
                                <th>Quantity</th>
                                <th>Total Amount</th>
                                <th>Payment Method</th>
                                <th>Staff</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($transactions as $trans): ?>
                            <tr>
                                <td><?= date('Y-m-d H:i', strtotime($trans->transDate)) ?></td>
                                <td><?= htmlspecialchars($trans->ref) ?></td>
                                <td><?= number_format($trans->quantity) ?></td>
                                <td>GH₵<?= number_format($trans->totalMoneySpent, 2) ?></td>
                                <td><?= htmlspecialchars($trans->modeOfPayment) ?></td>
                                <td><?= htmlspecialchars($trans->staffName ?: 'N/A') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-info">
            No transactions found for the selected date range.
        </div>
        <?php endif; ?>
    </div>
</div>

