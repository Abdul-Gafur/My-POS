<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Items Report - Print</title>
    <style>
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none !important; }
            @page { 
                margin: 1cm;
                size: <?= isset($orientation) && $orientation == 'landscape' ? 'A4 landscape' : 'A4' ?>;
            }
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 10px;
        }
        .print-button {
            margin: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .print-button:hover {
            background-color: #0056b3;
        }
        .instructions {
            margin: 10px;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
    <script>
        window.onload = function() {
            // Auto-trigger print dialog
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</head>
<body>
    <div class="no-print instructions">
        <h3>Items Report - Ready to Print/Export as PDF</h3>
        <p><strong>Instructions:</strong></p>
        <ul>
            <li>Click the "Print" button below or use Ctrl+P (Cmd+P on Mac)</li>
            <li>In the print dialog, select "Save as PDF" as the destination</li>
            <li>The PDF will include all formatting and can be saved with the filename: <?= isset($filename) ? $filename : 'items_report.pdf' ?></li>
        </ul>
        <button class="print-button" onclick="window.print()">Print / Save as PDF</button>
    </div>
    <?= $html ?>
</body>
</html>

