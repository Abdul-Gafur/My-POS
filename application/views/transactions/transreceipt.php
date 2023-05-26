<?php
defined('BASEPATH') or exit('');
?>
<?php if ($allTransInfo): ?>
<?php $sn = 1; ?>
<div id="transReceiptToPrint" style="font-family: arial; font-weight: 700" <div class="row">
    <div class="col-xs-12 text-center">
        <center style='margin-bottom:5px'><img src="<?= base_url() ?>public/images/receipt_logo2.png" alt="logo"
                class="img-responsive" width="45px"></center>
        <span style="border: 2px solid black; padding-left: 20px; padding-right: 20px; font-size: 16px;">BIG
            MOOMEN ENT</span>
        <div style="font-size: 10px; font-weight: 700; line-height: 1.7;">
            Dealers in Cosmetics, Provisions, Rice and General Suppliers of Goods <br>
            LOCATION: Adjacent Butchers House, Wa-Market <br>
            Post Office Box 621, Wa-UWR<br>
            CONTACTS: 0543098004 / 0541915579
        </div>
    </div>
</div>
<hr style='margin-top:2px; margin-bottom:2px; background-color: gray; color: gray; height: 1px;'>

<!-- Inside Wa Market - Adjacent Butchers House, +233 54 309 8004 / +233 054 191 5579 -->
<div class="row" style="margin-top:2px">
    <div class="col-sm-12">
        <label>Receipt No:</label>
        <span><?= isset($ref) ? $ref : "" ?></span>
    </div>
</div>
<div class="row" style="margin-top:2px">
    <div class="col-sm-12">
        <label>Date:</label>
        <?= isset($transDate) ? date('jS M, Y h:i:sa', strtotime($transDate)) : "" ?>
    </div>
</div>

<div class="row" style="font-size: 10px; font-family: arial; font-weight: 800; background-color: black; color: white">
    <div class="col-xs-4" style="width: 38%;">ITEM</div>
    <div class="col-xs-4" style="width: 8%;">QTY</div>
    <div class="col-xs-4" style="width: 20%;">PRC(₵)</div>
    <div class="col-xs-4" style="width: 20%;">TOTAL(₵)</div>
</div>
<hr style='margin-top:2px; margin-bottom:0px'>
<?php $init_total = 0; ?>
<?php foreach ($allTransInfo as $get): ?>

<div class="row" style="font-size: 10px; font-family: arial; font-weight: 700;">
    <div class="col-xs-4" style="width: 38%;"><?= ellipsize($get['itemName'], 10); ?></div>
    <div class="col-xs-4" style="width: 8%;"><?= $get['quantity'] ?></div>
    <div class="col-xs-4" style="width: 20%;"><?= number_format($get['unitPrice'], 2) ?></div>
    <div class="col-xs-4" style="width: 20%;"><?= number_format($get['totalPrice'], 2) ?></div>
</div>
<?php $init_total += $get['totalPrice']; ?>
<?php endforeach; ?>
<hr style='margin-top:10px; margin-bottom:0px'>
<div class="row">
    <div class="col-xs-12 text-right">
        <b>Total: GH₵<?= isset($init_total) ? number_format($init_total, 2) : 0 ?></b>
    </div>
</div>
<hr style='margin-top:2px; margin-bottom:0px'>
<div class="row">
    <div class="col-xs-12 text-right">
        <b>Discount(GH₵): (<?= $discountPercentage ?>%)<?= isset($discountAmount) ? number_format($discountAmount, 2) : 0 ?></b>
    </div>
</div>
<!-- <div class="row">
    <div class="col-xs-12 text-right">
        <?php if ($vatPercentage > 0): ?>
        <b>VAT(<?= $vatPercentage ?>%): GH₵<?= isset($vatAmount) ? number_format($vatAmount, 2) : "" ?></b>
        <?php else: ?>
        VAT inclusive
        <?php endif; ?>
    </div>
</div> -->
<div class="row">
    <div class="col-xs-12 text-right">
        <b>FINAL TOTAL(GH₵):<?= isset($cumAmount) ? number_format($cumAmount, 2) : "" ?></b>
    </div>
</div>
<hr style='margin-top:5px; margin-bottom:0px'>
<div class="row margin-top-5">
    <div class="col-xs-12">
        <b>Mode of Payment: <?= isset($_mop) ? str_replace("_", " ", $_mop) : "" ?></b>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <b>Amount Paid: GH₵<?= isset($amountTendered) ? number_format($amountTendered, 2) : "" ?></b>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <b>Balance Given: GH₵<?= isset($changeDue) ? number_format($changeDue, 2) : "" ?></b>
    </div>
</div>
<hr style='margin-top:5px; margin-bottom:0px'>
<!-- <div class="row margin-top-5">
    <div class="col-xs-12">
        <b>Customer Name: <?= $cust_name ?></b>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <b>Customer Phone: <?= $cust_phone ?></b>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <b>Customer Email: <?= $cust_email ?></b>
    </div>
</div> -->
<br>
<div class="row">
    <b class="col-xs-12 text-center">GOODS PAID ARE NOT REFUNDABLE <br>THANK YOU</b>
</div>
<div>
    <i style="font-size: 10px;"> System Developed By: Abdul-Gafur Saeed <br>
        Contact: 054732237 / 0200957200
    </i>
</div>
</div>
<br class="hidden-print">
<div class="row hidden-print">
    <div class="col-sm-12">
        <div class="text-center">
            <button type="button" class="btn btn-primary ptr">
                <i class="fa fa-print"></i> Print Receipt
            </button>

            <button type="button" data-dismiss='modal' class="btn btn-danger">
                <i class="fa fa-close"></i> Close
            </button>
        </div>
    </div>
</div>
<br class="hidden-print">
<?php endif; ?>