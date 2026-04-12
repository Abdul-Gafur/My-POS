<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2025-11-03 15:14:15 --> Severity: Warning --> file_get_contents(/Applications/MAMP/htdocs/elp/system/sqlite/1410inventory.sqlite): failed to open stream: No such file or directory /Applications/MAMP/htdocs/elp/application/controllers/Misc.php 73
ERROR - 2025-11-03 15:46:18 --> Severity: error --> Exception: Class Activitylog already exists and doesn't extend CI_Model /Applications/MAMP/htdocs/elp/system/core/Loader.php 349
ERROR - 2025-11-03 15:47:58 --> Query error: Unknown column 'archived' in 'field list' - Invalid query: UPDATE `items` SET `archived` = 1
WHERE `id` = '8'
ERROR - 2025-11-03 15:48:07 --> Query error: Unknown column 'archived' in 'field list' - Invalid query: UPDATE `items` SET `archived` = 1
WHERE `id` = '8'
ERROR - 2025-11-03 15:48:13 --> Query error: Unknown column 'dateAdded' in 'field list' - Invalid query: INSERT INTO `eventlog` (dateAdded, `event`, `eventRowIdOrRef`, `eventDesc`, `eventTable`, `staffInCharge`) VALUES (NOW(), 'Bulk deleted', '8', 'Bulk deleted: Item \'Unknown\' was deleted', 'items', '1')
ERROR - 2025-11-03 15:48:39 --> Query error: Unknown column 'dateAdded' in 'field list' - Invalid query: INSERT INTO `eventlog` (dateAdded, `event`, `eventRowIdOrRef`, `eventDesc`, `eventTable`, `staffInCharge`) VALUES (NOW(), 'Bulk deleted', '16', 'Bulk deleted: Item \'Unknown\' was deleted', 'items', '1')
ERROR - 2025-11-03 16:04:46 --> Severity: error --> Exception: Class Activitylog already exists and doesn't extend CI_Model /Applications/MAMP/htdocs/elp/system/core/Loader.php 349
ERROR - 2025-11-03 16:05:13 --> Severity: error --> Exception: Class Activitylog already exists and doesn't extend CI_Model /Applications/MAMP/htdocs/elp/system/core/Loader.php 349
ERROR - 2025-11-03 16:09:02 --> Severity: error --> Exception: Class Activitylog already exists and doesn't extend CI_Model /Applications/MAMP/htdocs/elp/system/core/Loader.php 349
ERROR - 2025-11-03 16:10:01 --> Query error: Unknown column 'archived' in 'field list' - Invalid query: UPDATE `items` SET `archived` = 1
WHERE `id` = '16'
ERROR - 2025-11-03 16:10:07 --> Query error: Unknown column 'dateAdded' in 'field list' - Invalid query: INSERT INTO `eventlog` (dateAdded, `event`, `eventRowIdOrRef`, `eventDesc`, `eventTable`, `staffInCharge`) VALUES (NOW(), 'Bulk deleted', '16', 'Bulk deleted: Item \'Bluetooth Headphones Over-Ear\' was deleted', 'items', '1')
ERROR - 2025-11-03 16:10:32 --> Query error: Unknown column 'dateAdded' in 'field list' - Invalid query: INSERT INTO `eventlog` (dateAdded, `event`, `eventRowIdOrRef`, `eventDesc`, `eventTable`, `staffInCharge`) VALUES (NOW(), 'Bulk deleted', '16', 'Bulk deleted: Item \'Bluetooth Headphones Over-Ear\' was deleted', 'items', '1')
ERROR - 2025-11-03 16:15:25 --> Severity: error --> Exception: Unable to locate the model you have specified: Activitylog_model /Applications/MAMP/htdocs/elp/system/core/Loader.php 344
ERROR - 2025-11-03 16:15:28 --> Severity: error --> Exception: Unable to locate the model you have specified: Activitylog_model /Applications/MAMP/htdocs/elp/system/core/Loader.php 344
ERROR - 2025-11-03 16:18:59 --> Severity: error --> Exception: Unable to locate the model you have specified: Activitylog_model /Applications/MAMP/htdocs/elp/system/core/Loader.php 344
ERROR - 2025-11-03 16:21:06 --> Query error: Unknown column 'dateAdded' in 'field list' - Invalid query: SELECT DATE(dateAdded) as activity_date, COUNT(*) as count
FROM `eventlog`
WHERE DATE(dateAdded) >= '2025-10-04'
GROUP BY DATE(dateAdded)
ORDER BY `activity_date` ASC
ERROR - 2025-11-03 16:23:49 --> Query error: Unknown column 'dateAdded' in 'field list' - Invalid query: SELECT dateAdded FROM eventlog LIMIT 1
