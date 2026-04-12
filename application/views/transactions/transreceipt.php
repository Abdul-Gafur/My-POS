<?php
defined('BASEPATH') or exit('');
?>
<?php if ($allTransInfo): ?>
<?php $sn = 1; ?>
<div id="transReceiptToPrint" style="font-family: 'Segoe UI', Arial, sans-serif; max-width: 80mm; margin: 0 auto; padding: 10px;">
<div class="row">
    <div class="col-xs-12 text-center" style="border-bottom: 2px dashed #333; padding-bottom: 10px; margin-bottom: 10px;">
        <center style='margin-bottom:8px'>
            <img src="<?= base_url() ?>public/images/receipt_logo2.png" alt="logo"
                class="img-responsive" style="max-width: 50px; margin: 0 auto;">
        </center>
        <h3 style="margin: 5px 0; font-size: 18px; font-weight: bold; color: #333;">EXTRA LIFE PHARMACY</h3>
        <div style="font-size: 11px; font-weight: 600; line-height: 1.6; color: #666;">
            <strong>LOCATION:</strong> Hamile, A-line <br>
            Post Office Box LW 43, Hamile<br>
            <strong>CONTACTS:</strong> 0247667491 / 0207856467
        </div>
    </div>
</div>
<hr style='margin-top:2px; margin-bottom:2px; background-color: gray; color: gray; height: 1px;'>

<!-- Inside Wa Market - Adjacent Butchers House, +233 54 309 8004 / +233 054 191 5579 -->
<div class="row" style="margin-top:8px; font-size: 11px;">
    <div class="col-sm-6">
        <strong>Receipt No:</strong> <?= isset($ref) ? $ref : "" ?>
    </div>
    <div class="col-sm-6 text-right">
        <strong>Date:</strong> <?= isset($transDate) ? date('jS M, Y h:i:sa', strtotime($transDate)) : "" ?>
    </div>
</div>

<div class="row" style="font-size: 10px; font-weight: 700; background-color: #333; color: white; padding: 8px 5px; margin-top: 10px;">
    <div class="col-xs-4" style="width: 40%;">ITEM</div>
    <div class="col-xs-4" style="width: 15%; text-align: center;">QTY</div>
    <div class="col-xs-4" style="width: 20%; text-align: right;">PRICE</div>
    <div class="col-xs-4" style="width: 25%; text-align: right;">TOTAL</div>
</div>
<div style="border-bottom: 1px dashed #ccc; margin: 5px 0;"></div>
<?php $init_total = 0; ?>
<?php foreach ($allTransInfo as $get): ?>

<div class="row" style="font-size: 10px; font-weight: 600; padding: 4px 0; border-bottom: 1px dotted #eee;">
    <div class="col-xs-4" style="width: 40%;"><?= htmlspecialchars($get['itemName']); ?></div>
    <div class="col-xs-4" style="width: 15%; text-align: center;"><?= $get['quantity'] ?></div>
    <div class="col-xs-4" style="width: 20%; text-align: right;"><?= number_format($get['unitPrice'], 2) ?></div>
    <div class="col-xs-4" style="width: 25%; text-align: right; font-weight: 700;"><?= number_format($get['totalPrice'], 2) ?></div>
</div>
<?php $init_total += $get['totalPrice']; ?>
<?php endforeach; ?>
<hr style='margin-top:10px; margin-bottom:0px'>
<div style="border-top: 2px solid #333; margin-top: 10px; padding-top: 8px;">
<div class="row" style="font-size: 11px; margin-bottom: 3px;">
    <div class="col-xs-8 text-left"><strong>Subtotal:</strong></div>
    <div class="col-xs-4 text-right"><strong>GH₵<?= isset($init_total) ? number_format($init_total, 2) : 0 ?></strong></div>
</div>
<?php if (isset($discountPercentage) && $discountPercentage > 0): ?>
<div class="row" style="font-size: 11px; margin-bottom: 3px; color: #d9534f;">
    <div class="col-xs-8 text-left"><strong>Discount (<?= $discountPercentage ?>%):</strong></div>
    <div class="col-xs-4 text-right"><strong>-GH₵<?= isset($discountAmount) ? number_format($discountAmount, 2) : 0 ?></strong></div>
</div>
<?php endif; ?>
<!-- <div class="row">
    <div class="col-xs-12 text-right">
        <?php if ($vatPercentage > 0): ?>
        <b>VAT(<?= $vatPercentage ?>%): GH₵<?= isset($vatAmount) ? number_format($vatAmount, 2) : "" ?></b>
        <?php else: ?>
        VAT inclusive
        <?php endif; ?>
    </div>
</div> -->
<div class="row" style="font-size: 13px; font-weight: bold; background-color: #f5f5f5; padding: 8px 5px; margin-top: 5px; border-top: 2px solid #333; border-bottom: 2px solid #333;">
    <div class="col-xs-8 text-left"><strong>TOTAL:</strong></div>
    <div class="col-xs-4 text-right"><strong>GH₵<?= isset($cumAmount) ? number_format($cumAmount, 2) : "" ?></strong></div>
</div>
</div>

<div style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed #ccc;">
<div class="row" style="font-size: 11px; margin-bottom: 4px;">
    <div class="col-xs-6"><strong>Payment Method:</strong></div>
    <div class="col-xs-6 text-right"><?= isset($_mop) ? str_replace("_", " ", $_mop) : "" ?></div>
</div>
<div class="row" style="font-size: 11px; margin-bottom: 4px;">
    <div class="col-xs-6"><strong>Amount Paid:</strong></div>
    <div class="col-xs-6 text-right">GH₵<?= isset($amountTendered) ? number_format($amountTendered, 2) : "" ?></div>
</div>
<div class="row" style="font-size: 11px; margin-bottom: 4px;">
    <div class="col-xs-6"><strong>Change:</strong></div>
    <div class="col-xs-6 text-right">GH₵<?= isset($changeDue) ? number_format($changeDue, 2) : "" ?></div>
</div>
</div>
<?php if (!empty($cust_name) || !empty($cust_phone) || !empty($cust_email)): ?>
<div style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed #ccc;">
    <div class="row" style="font-size: 11px; margin-bottom: 3px;">
        <div class="col-xs-12"><strong>Customer Information:</strong></div>
    </div>
    <?php if (!empty($cust_name)): ?>
    <div class="row" style="font-size: 10px; margin-bottom: 2px;">
        <div class="col-xs-12"><strong>Name:</strong> <?= htmlspecialchars($cust_name) ?></div>
    </div>
    <?php endif; ?>
    <?php if (!empty($cust_phone)): ?>
    <div class="row" style="font-size: 10px; margin-bottom: 2px;">
        <div class="col-xs-12"><strong>Phone:</strong> <?= htmlspecialchars($cust_phone) ?></div>
    </div>
    <?php endif; ?>
    <?php if (!empty($cust_email)): ?>
    <div class="row" style="font-size: 10px; margin-bottom: 2px;">
        <div class="col-xs-12"><strong>Email:</strong> <?= htmlspecialchars($cust_email) ?></div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
<div style="margin-top: 15px; padding-top: 10px; border-top: 2px dashed #333;">
    <p class="text-center" style="font-size: 11px; font-weight: 700; margin-bottom: 5px;">
        GOODS PAID ARE NOT REFUNDABLE
    </p>
    <p class="text-center" style="font-size: 12px; font-weight: 700; color: #333; margin-top: 8px;">
        THANK YOU FOR YOUR PATRONAGE!
    </p>
    <p class="text-center" style="font-size: 9px; color: #999; margin-top: 10px; font-style: italic;">
        System Developed By: Abdul-Gafur<br>
        Contact: 0547322637
    </p>
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