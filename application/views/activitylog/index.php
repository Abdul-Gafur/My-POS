<?php defined('BASEPATH') OR exit(''); ?>

<div class="row hidden-print">
    <div class="col-sm-12">
        <div class="pwell">
            <!-- Statistics Cards -->
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-sm-3">
                    <div class="panel panel-info">
                        <div class="panel-body" style="background-color: #337ab7; color: white; height: 80px;">
                            <div class="pull-left"><i class="fa fa-history fa-2x"></i></div>
                            <div class="pull-right text-right">
                                <div style="font-size: 24px; font-weight: bold;"><?= isset($statistics['total_activities']) ? number_format($statistics['total_activities']) : 0 ?></div>
                                <div>Total Activities</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="panel panel-success">
                        <div class="panel-body" style="background-color: #5cb85c; color: white; height: 80px;">
                            <div class="pull-left"><i class="fa fa-users fa-2x"></i></div>
                            <div class="pull-right text-right">
                                <div style="font-size: 24px; font-weight: bold;"><?= isset($statistics['by_staff']) ? count($statistics['by_staff']) : 0 ?></div>
                                <div>Active Staff</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="panel panel-warning">
                        <div class="panel-body" style="background-color: #f0ad4e; color: white; height: 80px;">
                            <div class="pull-left"><i class="fa fa-database fa-2x"></i></div>
                            <div class="pull-right text-right">
                                <div style="font-size: 24px; font-weight: bold;"><?= isset($statistics['by_table']) ? count($statistics['by_table']) : 0 ?></div>
                                <div>Modules Tracked</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="panel panel-danger">
                        <div class="panel-body" style="background-color: #d9534f; color: white; height: 80px;">
                            <div class="pull-left"><i class="fa fa-calendar fa-2x"></i></div>
                            <div class="pull-right text-right">
                                <div style="font-size: 24px; font-weight: bold;"><?= isset($statistics['daily']) ? count($statistics['daily']) : 0 ?></div>
                                <div>Days (Last 30)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4><i class="fa fa-filter"></i> Filters</h4>
                        </div>
                        <div class="panel-body">
                            <form id="activityLogFilters" class="form-inline">
                                <div class="form-group">
                                    <label>Event Type:</label>
                                    <input type="text" id="eventTypeFilter" class="form-control" placeholder="Search event...">
                                </div>
                                <div class="form-group">
                                    <label>Staff:</label>
                                    <select id="staffFilter" class="form-control">
                                        <option value="">All Staff</option>
                                        <?php if(isset($all_staff) && $all_staff): ?>
                                            <?php foreach($all_staff as $staff): ?>
                                                <option value="<?= $staff->id ?>"><?= $staff->first_name . ' ' . $staff->last_name ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Table:</label>
                                    <select id="tableFilter" class="form-control">
                                        <option value="">All Tables</option>
                                        <option value="items">Items</option>
                                        <option value="transactions">Transactions</option>
                                        <option value="admin">Administrators</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Date From:</label>
                                    <input type="date" id="dateFromFilter" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Date To:</label>
                                    <input type="date" id="dateToFilter" class="form-control">
                                </div>
                                <div class="form-group">
                                    <button type="button" id="applyFilters" class="btn btn-primary">
                                        <i class="fa fa-search"></i> Apply Filters
                                    </button>
                                    <button type="button" id="clearFilters" class="btn btn-default">
                                        <i class="fa fa-times"></i> Clear
                                    </button>
                                    <?php if($this->permissions->hasPermission('reports', 'export')): ?>
                                    <button type="button" id="exportLogs" class="btn btn-success">
                                        <i class="fa fa-download"></i> Export
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Log Table -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4><i class="fa fa-list"></i> Activity Log</h4>
                        </div>
                        <div class="panel-body">
                            <div id="activityLogTable"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>public/js/activitylog.js"></script>

