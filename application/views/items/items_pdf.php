<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Items Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: #fff;
            font-weight: bold;
            font-size: 9px;
        }
        td {
            font-size: 9px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
            font-size: 9px;
        }
        .summary {
            margin-top: 15px;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ITEMS INVENTORY REPORT</h1>
        <p><strong>Generated:</strong> <?= $generated_date ?></p>
        <p><strong>Total Items:</strong> <?= count($allItems) ?></p>
    </div>
    
    <?php if($allItems): ?>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">SN</th>
                <th style="width: 20%;">Item Name</th>
                <th style="width: 12%;">Item Code</th>
                <th style="width: 25%;">Description</th>
                <th style="width: 10%;" class="text-right">Quantity</th>
                <th style="width: 12%;" class="text-right">Unit Price (GH₵)</th>
                <th style="width: 16%;" class="text-right">Total Value (GH₵)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sn = 1;
            foreach($allItems as $item): 
                $totalValue = $item->quantity * $item->unitPrice;
            ?>
            <tr>
                <td class="text-center"><?= $sn++ ?></td>
                <td><?= htmlspecialchars($item->name) ?></td>
                <td><?= htmlspecialchars($item->code) ?></td>
                <td><?= htmlspecialchars(word_limiter($item->description, 30)) ?></td>
                <td class="text-right"><?= number_format($item->quantity, 0) ?></td>
                <td class="text-right"><?= number_format($item->unitPrice, 2) ?></td>
                <td class="text-right"><strong><?= number_format($totalValue, 2) ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="summary">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; text-align: right; width: 70%;"><strong>Total Inventory Value:</strong></td>
                <td style="border: none; text-align: right; width: 30%;"><strong>GH₵<?= number_format($cum_total, 2) ?></strong></td>
            </tr>
        </table>
    </div>
    <?php else: ?>
    <p style="text-align: center; padding: 20px;">No items found in inventory.</p>
    <?php endif; ?>
    
    <div class="footer">
        <p style="text-align: center;">
            <strong>POS System</strong> | Generated on <?= date('F d, Y h:i A') ?>
        </p>
    </div>
</body>
</html>

