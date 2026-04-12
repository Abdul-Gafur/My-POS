<?php
defined('BASEPATH') or exit('');

$total_earned = 0;

?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Items Report</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url() ?>public/images/logo1f.png">
    <!-- favicon ends --->

    <!--- LOAD FILES -->
    <?php if ((stristr($_SERVER['HTTP_HOST'], "localhost") !== FALSE) || (stristr($_SERVER['HTTP_HOST'], "192.168.") !== FALSE) || (stristr($_SERVER['HTTP_HOST'], "127.0.0.") !== FALSE)) : ?>
        <link rel="stylesheet" href="<?= base_url() ?>public/bootstrap/css/bootstrap.min.css">

    <?php else : ?>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <?php endif; ?>

    <!-- custom CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>public/css/main.css">
</head>

<body>
    <div class="container margin-top-5">
        <div class="row">
            <div class="col-xs-12 text-right hidden-print">
                <button class="btn btn-primary btn-sm" onclick="window.print()">Print Report</button>
            </div>
        </div>


        <div class="row margin-top-5">
            <div class="col-xs-12 table-responsive">
                <div class="panel panel-primary">
                    <!-- Default panel contents -->
                    <div class="panel-heading text-center">
                        All Items Report
                    </div>
                    <?php if ($allItems) : ?>
                        <div class="table table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>ITEM NAME</th>
                                        <th>ITEM CODE</th>
                                        <th>DESCRIPTION</th>
                                        <th>QTY IN STOCK</th>
                                        <th>UNIT PRICE (GH₵)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $sn = 1;?>
                                    <?php foreach ($allItems as $get) : ?>
                                        <tr>
                                            <input type="hidden" value="<?= $get->id ?>" class="curItemId">
                                            <th class="itemSN"><?= $sn ?>.</th>
                                            <td><span id="itemName-<?= $get->id ?>"><?= $get->name ?></span></td>
                                            <td><span id="itemCode-<?= $get->id ?>"><?= $get->code ?></td>
                                            <td>
                                                <span id="itemDesc-<?= $get->id ?>" data-toggle="tooltip" title="<?= $get->description ?>" data-placement="auto">
                                                    <?= word_limiter($get->description, 15) ?>
                                                </span>
                                            </td>
                                            <td class="<?= $get->quantity <= 10 ? 'bg-danger' : ($get->quantity <= 25 ? 'bg-warning' : '') ?>">
                                                <span id="itemQuantity-<?= $get->id ?>"><?= $get->quantity ?></span>
                                            </td>
                                            <td>GH₵<span id="itemPrice-<?= $get->id ?>"><?= number_format($get->unitPrice, 2) ?></span></td>
                                        </tr>
                                        <?php $sn++; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- table div end--->
                    <?php else : ?>
                        <ul>
                            <li>No Transaction Found Within Specified Dates</li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom: 10px">
            <div class="col-xs-6">
                <button class="btn btn-primary btn-sm hidden-print" onclick="window.print()">Print Report</button>
            </div>

        </div>
    </div>
    <!--- panel end-->
</body>

</html>