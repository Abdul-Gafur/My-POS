<?php defined('BASEPATH') OR exit('') ?>

<div class='col-sm-6'>
    <?= isset($range) && !empty($range) ? $range : ""; ?>
</div>

<div class='col-sm-6 text-right'><b>Items Total Worth/Price:</b> GH₵<?=$cum_total ? number_format($cum_total, 2) : '0.00'?></div>

<div class='col-xs-12'>
    <div class="panel panel-primary">
        <!-- Default panel contents -->
        <div class="panel-heading">Items</div>
        <?php if($allItems): ?>
        <div class="table table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <?php if($this->permissions->hasPermission('items', 'bulk_update') || $this->permissions->hasPermission('items', 'bulk_delete')): ?>
                        <th style="width: 30px;">
                            <input type="checkbox" id="selectAllItems" title="Select All">
                        </th>
                        <?php endif; ?>
                        <th>SN</th>
                        <th>ITEM NAME</th>
                        <th>ITEM CODE</th>
                        <th>DESCRIPTION</th>
                        <th>QTY IN STOCK</th>
                        <th>UNIT PRICE (GH₵)</th>
                        <th>TOTAL SOLD</th>
                        <th>TOTAL EARNED ON ITEM (GH₵)</th>
                        <th>UPDATE QUANTITY</th>
                        <th>EDIT</th>
                        <th>DELETE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($allItems as $get): ?>
                    <tr>
                        <?php if($this->permissions->hasPermission('items', 'bulk_update') || $this->permissions->hasPermission('items', 'bulk_delete')): ?>
                        <td>
                            <input type="checkbox" class="item-checkbox" data-item-id="<?=$get->id?>" title="Select Item">
                        </td>
                        <?php endif; ?>
                        <input type="hidden" value="<?=$get->id?>" class="curItemId">
                        <th class="itemSN"><?=$sn?>.</th>
                        <td><span id="itemName-<?=$get->id?>"><?=$get->name?></span></td>
                        <td><span id="itemCode-<?=$get->id?>"><?=$get->code?></td>
                        <td>
                            <span id="itemDesc-<?=$get->id?>" data-toggle="tooltip" title="<?=$get->description?>" data-placement="auto">
                                <?=word_limiter($get->description, 15)?>
                            </span>
                        </td>
                        <td class="<?=$get->quantity <= 10 ? 'bg-danger' : ($get->quantity <= 25 ? 'bg-warning' : '')?>">
                            <span id="itemQuantity-<?=$get->id?>"><?=$get->quantity?></span>
                        </td>
                        <td>GH₵<span id="itemPrice-<?=$get->id?>"><?=number_format($get->unitPrice, 2)?></span></td>
                        <td>
                            <?php
                            $this->db->select_sum('quantity');
                            $this->db->where('itemCode', $get->code);
                            $qty_query = $this->db->get('transactions');
                            $totalSold = $qty_query->num_rows() > 0 && $qty_query->row()->quantity ? $qty_query->row()->quantity : 0;
                            echo $totalSold;
                            ?>
                        </td>
                        <td>
                            <?php
                            $this->db->select_sum('totalPrice');
                            $this->db->where('itemCode', $get->code);
                            $price_query = $this->db->get('transactions');
                            $totalEarned = $price_query->num_rows() > 0 && $price_query->row()->totalPrice ? $price_query->row()->totalPrice : 0;
                            echo 'GH₵' . number_format($totalEarned, 2);
                            ?>
                        </td>
                        <td><a class="pointer updateStock" id="stock-<?=$get->id?>">Update Quantity</a></td>
                        <td class="text-center text-primary">
                            <span class="editItem" id="edit-<?=$get->id?>"><i class="fa fa-pencil pointer"></i> </span>
                        </td>
                        <td class="text-center"><i class="fa fa-trash text-danger delItem pointer"></i></td>
                    </tr>
                    <?php $sn++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- table div end-->
        <?php else: ?>
        <ul><li>No items</li></ul>
        <?php endif; ?>
    </div>
    <!--- panel end-->
</div>

<!---Pagination div-->
<div class="col-sm-12 text-center">
    <ul class="pagination">
        <?= isset($links) ? $links : "" ?>
    </ul>
</div>
