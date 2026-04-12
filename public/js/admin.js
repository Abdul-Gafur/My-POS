'use strict';

$(document).ready(function(){
    checkDocumentVisibility(checkLogin);//check document visibility in order to confirm user's log in status
	
	
    //load all admin once the page is ready
    //function header: laad_(url)
    laad_();
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //reload the list of admin when fields are changed
    $("#adminListSortBy, #adminListPerPage").change(function(){
        displayFlashMsg("Please wait...", spinnerClass, "", "");
        laad_();
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //load and show page when pagination link is clicked
    $("#allAdmin").on('click', '.lnp', function(e){
        e.preventDefault();
		
        displayFlashMsg("Please wait...", spinnerClass, "", "");

        laad_($(this).attr('href'));

        return false;
    });
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //Check to ensure the password and retype password fields are the same
    $("#passwordDup").on('keyup change focusout focus focusin', function(){
        var orig = $("#passwordOrig").val();
        var dup = $("#passwordDup").val();
        
        if(dup !== orig){
            //show error
            $("#passwordDupErr").addClass('fa');
            $("#passwordDupErr").addClass('fa-times');
            $("#passwordDupErr").removeClass('fa-check');
            $("#passwordDupErr").css('color', 'red');
            $("#passwordDupErr").html("");
        }
        
        else{
            //show success
            $("#passwordDupErr").addClass('fa');
            $("#passwordDupErr").addClass('fa-check');
            $("#passwordDupErr").removeClass('fa-times');
            $("#passwordDupErr").css('color', 'green');
            $("#passwordDupErr").html("");
        }
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles the addition of new admin details .i.e. when "add admin" button is clicked
    $("#addAdminSubmit").click(function(e){
        e.preventDefault();
        
        //reset all error msgs in case they are set
        changeInnerHTML(['firstNameErr', 'lastNameErr', 'emailErr', 'roleErr', 'mobile1Err', 'mobile2Err', 'passwordOrigErr', 'passwordDupErr'],
        "");
        
        var firstName = $("#firstName").val();
        var lastName = $("#lastName").val();
        var email = $("#email").val();
        var role = $("#role").val();
        var mobile1 = $("#mobile1").val();
        var mobile2 = $("#mobile2").val();
        var passwordOrig = $("#passwordOrig").val();
        var passwordDup = $("#passwordDup").val();
        
        //ensure all required fields are filled
        if(!firstName || !lastName || !email || !role || !mobile1 || !passwordOrig || !passwordDup){
            !firstName ? changeInnerHTML('firstNameErr', "required") : "";
            !lastName ? changeInnerHTML('lastNameErr', "required") : "";
            !email ? changeInnerHTML('emailErr', "required") : "";
            !role ? changeInnerHTML('roleErr', "required") : "";
            !mobile1 ? changeInnerHTML('mobile1Err', "required") : "";
            !passwordOrig ? changeInnerHTML('passwordOrigErr', "required") : "";
            !passwordDup ? changeInnerHTML('passwordDupErr', 'required') : "";
            
            return;
        }
        
        //display message telling user action is being processed
        $("#fMsgIcon").attr('class', spinnerClass);
        $("#fMsg").text(" Processing...");
        
        //make ajax request if all is well
        $.ajax({
            method: "POST",
            url: appRoot+"administrators/add",
            data: {firstName:firstName, lastName:lastName, email:email, role:role, mobile1:mobile1, mobile2:mobile2,
                passwordOrig:passwordOrig, passwordDup:passwordDup}
        }).done(function(returnedData){
            $("#fMsgIcon").removeClass();//remove spinner
                
            if(returnedData.status === 1){
                $("#fMsg").css('color', 'green').text(returnedData.msg);

                //reset the form
                document.getElementById("addNewAdminForm").reset();

                //close the modal
                setTimeout(function(){
                    $("#fMsg").text("");
                    $("#addNewAdminModal").modal('hide');
                }, 1000);

                //reset all error msgs in case they are set
                changeInnerHTML(['firstNameErr', 'lastNameErr', 'emailErr', 'roleErr', 'mobile1Err', 'mobile2Err', 'passwordOrigErr', 'passwordDupErr'],
                "");

                //refresh admin list table
                laad_();

            }

            else{
                //display error message returned
                $("#fMsg").css('color', 'red').html(returnedData.msg);

                //display individual error messages if applied
                $("#firstNameErr").text(returnedData.firstName);
                $("#lastNameErr").text(returnedData.lastName);
                $("#emailErr").text(returnedData.email);
                $("#roleErr").text(returnedData.role);
                $("#mobile1Err").text(returnedData.mobile1);
                $("#mobile2Err").text(returnedData.mobile2);
                $("#passwordOrigErr").text(returnedData.passwordOrig);
                $("#passwordDupErr").text(returnedData.passwordDup);
            }
        }).fail(function(){
            if(!navigator.onLine){
                $("#fMsg").css('color', 'red').text("Network error! Pls check your network connection");
            }
        });
    });
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles the updating of admin details
    $("#editAdminSubmit").click(function(e){
        e.preventDefault();
        
        //reset all error msgs in case they are set
        changeInnerHTML(['firstNameEditErr', 'lastNameEditErr', 'emailEditErr', 'roleEditErr', 'mobile1EditErr', 'mobile2EditErr', 'passwordEditErr', 'passwordEditConfirmErr'], "");

        var firstName = $("#firstNameEdit").val();
        var lastName = $("#lastNameEdit").val();
        var email = $("#emailEdit").val();
        var mobile1 = $("#mobile1Edit").val();
        var mobile2 = $("#mobile2Edit").val();
        var role = $("#roleEdit").val();
        var adminId = $("#adminId").val();
        var password = $("#passwordEdit").val();

        //ensure all required fields are filled (password is optional)
        if(!firstName || !lastName || !email || !role || !mobile1 || !adminId){
            !firstName ? changeInnerHTML('firstNameEditErr', "required") : "";
            !lastName ? changeInnerHTML('lastNameEditErr', "required") : "";
            !email ? changeInnerHTML('emailEditErr', "required") : "";
            !mobile1 ? changeInnerHTML('mobile1EditErr', "required") : "";
            !role ? changeInnerHTML('roleEditErr', "required") : "";
            !adminId ? $("#fMsgEdit").css('color', 'red').text("Admin ID missing. Please close and try again.") : "";

            return;
        }
        
        // Validate password only if provided and checkbox is checked
        if($("#changePasswordCheck").is(':checked')){
            if(!password || password.length < 8){
                changeInnerHTML('passwordEditErr', "Password must be at least 8 characters");
                return;
            }
            
            var passwordConfirm = $("#passwordEditConfirm").val();
            if(password !== passwordConfirm){
                changeInnerHTML('passwordEditConfirmErr', "Passwords do not match");
                return;
            }
        }

        //display message telling user action is being processed
        $("#fMsgEditIcon").attr('class', spinnerClass);
        $("#fMsgEdit").css('color', 'black').text(" Updating details...");

        //make ajax request if all is well
        // Only send password if it was provided and checkbox is checked
        var updateData = {
            firstName:firstName, 
            lastName:lastName, 
            email:email, 
            role:role, 
            mobile1:mobile1, 
            mobile2:mobile2, 
            adminId:adminId
        };
        
        // Only include password if user wants to change it
        if($("#changePasswordCheck").is(':checked') && password){
            updateData.password = password;
        }
        
        $.ajax({
            method: "POST",
            url: appRoot+"administrators/update",
            data: updateData
        }).done(function(returnedData){
            $("#fMsgEditIcon").removeClass();//remove spinner

            if(returnedData.status === 1){
                $("#fMsgEdit").css('color', 'green').text(returnedData.msg);

                //reset the form and close the modal
                setTimeout(function(){
                    $("#fMsgEdit").text("");
                    $("#editAdminModal").modal('hide');
                    // Reset form and password fields
                    $("#changePasswordCheck").prop('checked', false);
                    $('#passwordChangeFields').hide();
                    $('#passwordEdit, #passwordEditConfirm').val('');
                }, 1000);

                //reset all error msgs in case they are set
                changeInnerHTML(['firstNameEditErr', 'lastNameEditErr', 'emailEditErr', 'roleEditErr', 'mobile1EditErr', 'mobile2EditErr', 'passwordEditErr', 'passwordEditConfirmErr'], "");

                //refresh admin list table
                laad_();

            }

            else{
                //display error message returned
                $("#fMsgEdit").css('color', 'red').html(returnedData.msg || "Update failed. Please check the errors below.");

                //display individual error messages if applied
                if(returnedData.firstName) $("#firstNameEditErr").html(returnedData.firstName);
                if(returnedData.lastName) $("#lastNameEditErr").html(returnedData.lastName);
                if(returnedData.email) $("#emailEditErr").html(returnedData.email);
                if(returnedData.mobile1) $("#mobile1EditErr").html(returnedData.mobile1);
                if(returnedData.mobile2) $("#mobile2EditErr").html(returnedData.mobile2);
                if(returnedData.role) $("#roleEditErr").html(returnedData.role);
                if(returnedData.password) {
                    $("#passwordEditErr").html(returnedData.password);
                }
            }
        }).fail(function(xhr){
                $("#fMsgEditIcon").removeClass();
                if(!navigator.onLine){
                    $("#fMsgEdit").css('color', 'red').html("Network error! Pls check your network connection");
                } else {
                    $("#fMsgEdit").css('color', 'red').html("Request failed. Please try again.");
                    console.error("Update failed:", xhr);
                }
            });
    });
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    //handles admin search
    $("#adminSearch").on('keyup change', function(){
        var value = $(this).val();
        
        if(value){//search only if there is at least one char in input
            $.ajax({
                type: "get",
                url: appRoot+"search/adminsearch",
                data: {v:value},
                success: function(returnedData){
                    $("#allAdmin").html(returnedData.adminTable);
                }
            });
        }
        
        else{
            laad_();
        }
    });
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //When the toggle on/off button is clicked to change the account status of an admin (i.e. suspend or lift suspension)
    $("#allAdmin").on('click', '.suspendAdmin', function(){
        var ElemId = $(this).attr('id');
        
        var adminId = ElemId.split("-")[1];//get the adminId
        
        //show spinner
        $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");
        
        if(adminId){
            $.ajax({
                url: appRoot+"administrators/suspend",
                method: "POST",
                data: {_aId:adminId}
            }).done(function(returnedData){
                if(returnedData.status === 1){
                    //change the icon to "on" if it's "off" before the change and vice-versa
                    var newIconClass = returnedData._ns === 1 ? "fa fa-toggle-on pointer" : "fa fa-toggle-off pointer";
                    
                    //change the icon
                    $("#sus-"+returnedData._aId).html("<i class='"+ newIconClass +"'></i>");
                    
                }
                
                else{
                    console.log('err');
                }
            });
        }
    });
    
    
    /*
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    */
    
    
    //When the trash icon in front of an admin account is clicked on the admin list table (i.e. to delete the account)
    $("#allAdmin").on('click', '.deleteAdmin', function(){
        var confirm = window.confirm("Proceed?");
        
        if(confirm){
            var ElemId = $(this).attr('id');

            var adminId = ElemId.split("-")[1];//get the adminId

            //show spinner
            $("#"+ElemId).html("<i class='"+spinnerClass+"'</i>");

            if(adminId){
                $.ajax({
                    url: appRoot+"administrators/delete",
                    method: "POST",
                    data: {_aId:adminId}
                }).done(function(returnedData){
                    if(returnedData.status === 1){
                       
                        //change the icon to "undo delete" if it's "active" before the change and vice-versa
                        var newHTML = returnedData._nv === 1 ? "<a class='pointer'>Undo Delete</a>" : "<i class='fa fa-trash pointer'></i>";

                        //change the icon
                        $("#del-"+returnedData._aId).html(newHTML);

                    }

                    else{
                        alert(returnedData.status);
                    }
                });
            }
        }
    });
    
    
    /*
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    ******************************************************************************************************************************
    */
    
    
    //to launch the modal to allow for the editing of admin info
    $("#allAdmin").on('click', '.editAdmin', function(){
        
        var adminId = $(this).attr('id').split("-")[1];
        
        $("#adminId").val(adminId);
        
        //get info of admin with adminId and prefill the form with it
        var firstName = $(this).siblings(".firstName").html();
        var lastName = $(this).siblings(".lastName").html();
        var role = $(this).siblings(".adminRole").html();
        var email = $(this).siblings(".adminEmail").children('a').html();
        var mobile1 = $(this).siblings(".adminMobile1").html();
        var mobile2 = $(this).siblings(".adminMobile2").html();
        
        //prefill the form fields
        $("#firstNameEdit").val(firstName);
        $("#lastNameEdit").val(lastName);
        $("#emailEdit").val(email);
        $("#mobile1Edit").val(mobile1);
        $("#mobile2Edit").val(mobile2);
        $("#roleEdit").val(role);
        
        // Reset password fields
        $("#passwordEdit").val('');
        $("#passwordEditConfirm").val('');
        $("#changePasswordCheck").prop('checked', false);
        $('#passwordChangeFields').hide();
        $('#passwordEditErr, #passwordEditConfirmErr').html('');
        
        // Clear any previous messages
        $("#fMsgEdit").text('');
        $("#fMsgEditIcon").removeClass();
        changeInnerHTML(['firstNameEditErr', 'lastNameEditErr', 'emailEditErr', 'roleEditErr', 'mobile1EditErr', 'mobile2EditErr', 'passwordEditErr', 'passwordEditConfirmErr'], "");
        
        // Reset form defaults for formChanges detection
        setTimeout(function(){
            var form = document.getElementById('editAdminForm');
            if(form) {
                for(var i = 0; i < form.elements.length; i++){
                    var el = form.elements[i];
                    if(el.type !== 'button' && el.type !== 'submit' && el.type !== 'reset'){
                        if(el.type === 'checkbox' || el.type === 'radio'){
                            el.defaultChecked = el.checked;
                        } else {
                            el.defaultValue = el.value;
                        }
                    }
                }
            }
        }, 100);
        
        $("#editAdminModal").modal('show');
    });
    
    // Toggle password change fields
    $(document).on('change', '#changePasswordCheck', function(){
        if($(this).is(':checked')){
            $('#passwordChangeFields').slideDown();
            $('#passwordEdit, #passwordEditConfirm').addClass('checkField');
        } else {
            $('#passwordChangeFields').slideUp();
            $('#passwordEdit, #passwordEditConfirm').val('').removeClass('checkField');
            $('#passwordEditErr, #passwordEditConfirmErr').html('');
        }
    });
    
    // Password confirmation check
    $('#passwordEditConfirm').on('keyup', function(){
        var password = $('#passwordEdit').val();
        var confirm = $(this).val();
        
        if(confirm && password !== confirm){
            $('#passwordEditConfirmErr').html('Passwords do not match').css('color', 'red');
        } else if(confirm && password === confirm){
            $('#passwordEditConfirmErr').html('').css('color', '');
        }
    });
    
});



/*
***************************************************************************************************************************************
***************************************************************************************************************************************
***************************************************************************************************************************************
***************************************************************************************************************************************
***************************************************************************************************************************************
*/

/**
 * laad_ = "Load all administrators"
 * @returns {undefined}
 */
function laad_(url){
    var orderBy = $("#adminListSortBy").val().split("-")[0];
    var orderFormat = $("#adminListSortBy").val().split("-")[1];
    var limit = $("#adminListPerPage").val();
    
    $.ajax({
        type:'get',
        url: url ? url : appRoot+"administrators/laad_/",
        data: {orderBy:orderBy, orderFormat:orderFormat, limit:limit},
     }).done(function(returnedData){
            hideFlashMsg();
			
            $("#allAdmin").html(returnedData.adminTable);
        });
}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////