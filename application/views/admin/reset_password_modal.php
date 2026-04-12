<?php defined('BASEPATH') OR exit(''); ?>

<!-- Password Reset Modal -->
<div class='modal fade' id='resetPasswordModal' role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class='modal-header'>
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="text-center">Reset Password</h4>
                <div class="text-center">
                    <i id="resetPasswordIcon"></i>
                    <span id="resetPasswordMsg"></span>
                </div>
            </div>
            <div class="modal-body">
                <form id='resetPasswordForm' name='resetPasswordForm' role='form'>
                    <div class="form-group">
                        <label for='currentPasswordReset' class="control-label">Current Password</label>
                        <input type="password" id='currentPasswordReset' class="form-control" placeholder="Enter current password">
                        <span class="help-block errMsg" id="currentPasswordResetErr"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for='newPasswordReset' class="control-label">New Password</label>
                        <input type="password" id='newPasswordReset' class="form-control" placeholder="Enter new password (min 8 characters)">
                        <span class="help-block errMsg" id="newPasswordResetErr"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for='confirmPasswordReset' class="control-label">Confirm New Password</label>
                        <input type="password" id='confirmPasswordReset' class="form-control" placeholder="Confirm new password">
                        <span class="help-block errMsg" id="confirmPasswordResetErr"></span>
                    </div>
                    
                    <input type="hidden" id="resetPasswordAdminId">
                </form>
            </div>
            <div class="modal-footer">
                <button type='button' id='resetPasswordSubmit' class="btn btn-primary">Reset Password</button>
                <button type='button' class="btn btn-danger" data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Open reset password modal
    $('#resetPasswordBtn').on('click', function(e) {
        e.preventDefault();
        $('#resetPasswordModal').modal('show');
        $('#resetPasswordAdminId').val(''); // Empty means current user
    });
    
    // Handle password reset
    $('#resetPasswordSubmit').on('click', function(e) {
        e.preventDefault();
        
        var currentPassword = $('#currentPasswordReset').val();
        var newPassword = $('#newPasswordReset').val();
        var confirmPassword = $('#confirmPasswordReset').val();
        var adminId = $('#resetPasswordAdminId').val();
        
        // Reset error messages
        $('#currentPasswordResetErr, #newPasswordResetErr, #confirmPasswordResetErr').html('');
        
        // Validate
        if(!currentPassword){
            $('#currentPasswordResetErr').html('Required').css('color', 'red');
            return;
        }
        
        if(!newPassword || newPassword.length < 8){
            $('#newPasswordResetErr').html('Password must be at least 8 characters').css('color', 'red');
            return;
        }
        
        if(newPassword !== confirmPassword){
            $('#confirmPasswordResetErr').html('Passwords do not match').css('color', 'red');
            return;
        }
        
        // Show loading
        $('#resetPasswordIcon').attr('class', 'fa fa-spinner fa-spin');
        $('#resetPasswordMsg').text('Resetting password...');
        
        // Make AJAX request
        $.ajax({
            method: "POST",
            url: appRoot + "administrators/resetPassword",
            data: {
                admin_id: adminId,
                current_password: currentPassword,
                new_password: newPassword,
                confirm_password: confirmPassword
            }
        }).done(function(returnedData){
            $('#resetPasswordIcon').removeClass();
            
            if(returnedData.status === 1){
                $('#resetPasswordMsg').css('color', 'green').text(returnedData.msg);
                
                // Reset form and close modal after delay
                setTimeout(function(){
                    $('#resetPasswordForm')[0].reset();
                    $('#resetPasswordModal').modal('hide');
                    $('#resetPasswordMsg').text('');
                }, 1500);
            } else {
                $('#resetPasswordMsg').css('color', 'red').text(returnedData.msg);
                if(returnedData.current_password) {
                    $('#currentPasswordResetErr').html(returnedData.current_password).css('color', 'red');
                }
                if(returnedData.new_password) {
                    $('#newPasswordResetErr').html(returnedData.new_password).css('color', 'red');
                }
                if(returnedData.confirm_password) {
                    $('#confirmPasswordResetErr').html(returnedData.confirm_password).css('color', 'red');
                }
            }
        }).fail(function(){
            $('#resetPasswordMsg').css('color', 'red').text('Network error! Please check your connection');
        });
    });
    
    // Password confirmation check
    $('#confirmPasswordReset').on('keyup', function(){
        var newPassword = $('#newPasswordReset').val();
        var confirm = $(this).val();
        
        if(confirm && newPassword !== confirm){
            $('#confirmPasswordResetErr').html('Passwords do not match').css('color', 'red');
        } else if(confirm && newPassword === confirm){
            $('#confirmPasswordResetErr').html('').css('color', '');
        }
    });
});
</script>

