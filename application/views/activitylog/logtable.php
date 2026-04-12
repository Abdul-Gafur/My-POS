<?php defined('BASEPATH') OR exit(''); ?>

<?php if(isset($range) && !empty($range)): ?>
<div class="col-sm-12" style="margin-bottom: 10px;">
    <?= $range ?>
</div>
<?php endif; ?>

<div class="col-sm-12">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Date & Time</th>
                    <th>Event</th>
                    <th>Table/Module</th>
                    <th>Reference ID</th>
                    <th>Staff</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($allLogs) && $allLogs): ?>
                    <?php foreach($allLogs as $log): ?>
                        <tr>
                            <td><?= $sn ?>.</td>
                            <td><?= isset($log->dateAdded) && $log->dateAdded ? date('Y-m-d H:i:s', strtotime($log->dateAdded)) : 'N/A' ?></td>
                            <td><span class="label label-info"><?= htmlspecialchars($log->event) ?></span></td>
                            <td><?= htmlspecialchars($log->eventTable) ?></td>
                            <td><?= htmlspecialchars($log->eventRowIdOrRef) ?></td>
                            <td><?= htmlspecialchars($log->staff_name ?: 'System') ?></td>
                            <td style="max-width: 400px;"><?= strip_tags($log->eventDesc) ?></td>
                        </tr>
                        <?php $sn++; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No activity logs found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if(isset($links) && !empty($links)): ?>
<div class="col-sm-12 text-center" style="margin-top: 15px;">
    <?= $links ?>
</div>
<?php endif; ?>

