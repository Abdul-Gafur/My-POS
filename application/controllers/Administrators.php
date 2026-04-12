<?php
defined('BASEPATH') or exit('');

/**
 * Description of Administrators
 *
 *  
 *  
 */
class Administrators extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->genlib->checkLogin();

        $this->genlib->superOnly();

        $this->load->model(['admin']);
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    public function index()
    {
        $data['pageContent'] = $this->load->view('admin/admin', '', TRUE);
        $data['pageTitle'] = "Administrators";

        $this->load->view('main', $data);
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    /**
     * lac_ = "Load all administrators"
     */
    public function laad_()
    {
        //set the sort order
        $orderBy = $this->input->get('orderBy', TRUE) ? $this->input->get('orderBy', TRUE) : "first_name";
        $orderFormat = $this->input->get('orderFormat', TRUE) ? $this->input->get('orderFormat', TRUE) : "ASC";

        //count the total administrators in db (excluding the currently logged in admin)
        $totalAdministrators = count($this->admin->getAll());

        $this->load->library('pagination');

        $pageNumber = $this->uri->segment(3, 0); //set page number to zero if the page number is not set in the third segment of uri

        $limit = $this->input->get('limit', TRUE) ? $this->input->get('limit', TRUE) : 10; //show $limit per page
        $start = $pageNumber == 0 ? 0 : ($pageNumber - 1) * $limit; //start from 0 if pageNumber is 0, else start from the next iteration

        //call setPaginationConfig($totalRows, $urlToCall, $limit, $attributes) in genlib to configure pagination
        $config = $this->genlib->setPaginationConfig($totalAdministrators, "administrators/laad_", $limit, ['class' => 'lnp']);

        $this->pagination->initialize($config); //initialize the library class

        //get all customers from db
        $data['allAdministrators'] = $this->admin->getAll($orderBy, $orderFormat, $start, $limit);
        $data['range'] = $totalAdministrators > 0 ? ($start + 1) . "-" . ($start + count($data['allAdministrators'])) . " of " . $totalAdministrators : "";
        $data['links'] = $this->pagination->create_links(); //page links
        $data['sn'] = $start + 1;

        $json['adminTable'] = $this->load->view('admin/adminlist', $data, TRUE); //get view with populated customers table

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    /**
     * To add new admin
     */
    public function add()
    {
        $this->genlib->ajaxOnly();

        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('firstName', 'First name', ['required', 'trim', 'max_length[20]', 'strtolower', 'ucfirst'], ['required' => "required"]);
        $this->form_validation->set_rules('lastName', 'Last name', ['required', 'trim', 'max_length[20]', 'strtolower', 'ucfirst'], ['required' => "required"]);
        $this->form_validation->set_rules(
            'email',
            'E-mail',
            ['trim', 'required', 'valid_email', 'is_unique[admin.email]', 'strtolower'],
            ['required' => "required", 'is_unique' => 'E-mail exists']
        );
        $this->form_validation->set_rules('role', 'Role', ['required'], ['required' => "required"]);
        $this->form_validation->set_rules(
            'mobile1',
            'Phone number',
            ['required', 'trim', 'numeric', 'max_length[15]', 'min_length[11]', 'is_unique[admin.mobile1]'],
            ['required' => "required", 'is_unique' => "This number is already attached to an admin"]
        );
        $this->form_validation->set_rules('mobile2', 'Other number', ['trim', 'numeric', 'max_length[15]', 'min_length[11]']);
        $this->form_validation->set_rules('passwordOrig', 'Password', ['required', 'min_length[8]'], ['required' => "Enter password"]);
        $this->form_validation->set_rules('passwordDup', 'Password Confirmation', ['required', 'matches[passwordOrig]'], ['required' => "Please retype password"]);

        if ($this->form_validation->run() !== FALSE) {
            /**
             * insert info into db
             * function header: add($f_name, $l_name, $email, $password, $role, $mobile1, $mobile2)
             */
            $hashedPassword = password_hash(set_value('passwordOrig'), PASSWORD_BCRYPT);

            $inserted = $this->admin->add(
                set_value('firstName'),
                set_value('lastName'),
                set_value('email'),
                $hashedPassword,
                set_value('role'),
                set_value('mobile1'),
                set_value('mobile2')
            );


            $json = $inserted ?
                ['status' => 1, 'msg' => "Admin account successfully created"]
                :
                ['status' => 0, 'msg' => "Oops! Unexpected server error! Pls contact administrator for help. Sorry for the embarrassment"];
        } else {
            //return all error messages
            $json = $this->form_validation->error_array(); //get an array of all errors

            $json['msg'] = "One or more required fields are empty or not correctly filled";
            $json['status'] = 0;
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    /**
     * 
     */
    public function update()
    {
        $this->genlib->ajaxOnly();

        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('firstName', 'First name', ['required', 'trim', 'max_length[20]'], ['required' => "required"]);
        $this->form_validation->set_rules('lastName', 'Last name', ['required', 'trim', 'max_length[20]'], ['required' => "required"]);
        $this->form_validation->set_rules('mobile1', 'Phone number', [
            'required', 'trim', 'numeric', 'max_length[15]',
            'min_length[11]', 'callback_crosscheckMobile[' . $this->input->post('adminId', TRUE) . ']'
        ], ['required' => "required"]);
        $this->form_validation->set_rules('mobile2', 'Other number', ['trim', 'numeric', 'max_length[15]', 'min_length[11]']);
        $this->form_validation->set_rules('email', 'E-mail', ['required', 'trim', 'valid_email', 'callback_crosscheckEmail[' . $this->input->post('adminId', TRUE) . ']']);
        $this->form_validation->set_rules('role', 'Role', ['required', 'trim'], ['required' => "required"]);
        // Password is optional - only validate if provided
        $password = $this->input->post('password', TRUE);
        if (!empty($password)) {
            $this->form_validation->set_rules('password', 'Password', ['trim', 'min_length[8]']);
        }

        if ($this->form_validation->run() !== FALSE) {
            /**
             * update info in db
             * function header: update($admin_id, $first_name, $last_name, $email, $mobile1, $mobile2, $role)
             */

            $admin_id = $this->input->post('adminId', TRUE);

            // Only hash password if it's provided
            $password = $this->input->post('password', TRUE);
            $hashedPassword = '';
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            }

            $updated = $this->admin->update(
                $admin_id,
                set_value('firstName'),
                set_value('lastName'),
                set_value('email'),
                set_value('mobile1'),
                set_value('mobile2'),
                set_value('role'),
                $hashedPassword
            );
            
            // Log activity
            if ($updated) {
                $this->db->select('first_name, last_name');
                $this->db->where('id', $admin_id);
                $admin_query = $this->db->get('admin');
                $adminName = 'Unknown';
                if ($admin_query->num_rows() > 0) {
                    $admin_data = $admin_query->row();
                    $adminName = $admin_data->first_name . ' ' . $admin_data->last_name;
                }
                $desc = "Admin profile updated: " . $adminName;
                if (!empty($password)) {
                    $desc .= " (Password changed)";
                }
                $this->genmod->addevent("Admin Profile Update", $admin_id, $desc, "admin", $this->session->admin_id);
            }

            $json = $updated ?
                ['status' => 1, 'msg' => "Admin info successfully updated"]
                :
                ['status' => 0, 'msg' => "Oops! Unexpected server error! Pls contact administrator for help. Sorry for the embarrassment"];
        } else {
            //return all error messages
            $json = $this->form_validation->error_array(); //get an array of all errors

            $json['msg'] = "One or more required fields are empty or not correctly filled";
            $json['status'] = 0;
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */


    public function suspend()
    {
        $this->genlib->ajaxOnly();

        $admin_id = $this->input->post('_aId');
        $new_status = $this->genmod->gettablecol('admin', 'account_status', 'id', $admin_id) == 1 ? 0 : 1;

        $done = $this->admin->suspend($admin_id, $new_status);

        $json['status'] = $done ? 1 : 0;
        $json['_ns'] = $new_status;
        $json['_aId'] = $admin_id;

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }



    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    public function delete()
    {
        $this->genlib->ajaxOnly();

        $admin_id = $this->input->post('_aId');
        $new_value = $this->genmod->gettablecol('admin', 'deleted', 'id', $admin_id) == 1 ? 0 : 1;

        $done = $this->admin->delete($admin_id, $new_value);

        $json['status'] = $done ? 1 : 0;
        $json['_nv'] = $new_value;
        $json['_aId'] = $admin_id;

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    /**
     * Used as a callback while updating admin info to ensure 'mobile1' field does not contain a number already used by another admin
     * @param type $mobile_number
     * @param type $admin_id
     */
    public function crosscheckMobile($mobile_number, $admin_id)
    {
        //check db to ensure number was previously used for admin with $admin_id i.e. the same admin we're updating his details
        $adminWithNum = $this->genmod->getTableCol('admin', 'id', 'mobile1', $mobile_number);

        if ($adminWithNum == $admin_id) {
            //used for same admin. All is well.
            return TRUE;
        } else {
            $this->form_validation->set_message('crosscheckMobile', 'This number is already attached to an administrator');

            return FALSE;
        }
    }

    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    /**
     * Used as a callback while updating admin info to ensure 'email' field does not contain an email already used by another admin
     * @param type $email
     * @param type $admin_id
     */
    public function crosscheckEmail($email, $admin_id)
    {
        //check db to ensure email was previously used for admin with $admin_id i.e. the same admin we're updating his details
        $adminWithEmail = $this->genmod->getTableCol('admin', 'id', 'email', $email);

        if ($adminWithEmail == $admin_id) {
            //used for same admin. All is well.
            return TRUE;
        } else {
            $this->form_validation->set_message('crosscheckEmail', 'This email is already attached to an administrator');

            return FALSE;
        }
    }
    
    /**
     * Reset admin password (for current user or by admin)
     */
    public function resetPassword() {
        $this->genlib->ajaxOnly();
        
        $admin_id = $this->input->post('admin_id', TRUE);
        $current_password = $this->input->post('current_password', TRUE);
        $new_password = $this->input->post('new_password', TRUE);
        $confirm_password = $this->input->post('confirm_password', TRUE);
        
        // If no admin_id provided, assume current user
        if (empty($admin_id)) {
            $admin_id = $this->session->admin_id;
        }
        
        // Validate inputs
        if (empty($new_password) || strlen($new_password) < 8) {
            $json = ['status' => 0, 'msg' => 'New password must be at least 8 characters'];
            $this->output->set_content_type('application/json')->set_output(json_encode($json));
            return;
        }
        
        if ($new_password !== $confirm_password) {
            $json = ['status' => 0, 'msg' => 'Passwords do not match'];
            $this->output->set_content_type('application/json')->set_output(json_encode($json));
            return;
        }
        
        // If resetting own password, verify current password
        if ($admin_id == $this->session->admin_id && !empty($current_password)) {
            $this->db->select('password');
            $this->db->where('id', $admin_id);
            $pwd_query = $this->db->get('admin');
            if ($pwd_query->num_rows() === 0) {
                $json = ['status' => 0, 'msg' => 'Admin not found'];
                $this->output->set_content_type('application/json')->set_output(json_encode($json));
                return;
            }
            $current_password_hash = $pwd_query->row()->password;
            if (!password_verify($current_password, $current_password_hash)) {
                $json = ['status' => 0, 'msg' => 'Current password is incorrect'];
                $this->output->set_content_type('application/json')->set_output(json_encode($json));
                return;
            }
        }
        
        // Update password
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        $this->db->where('id', $admin_id);
        $this->db->update('admin', ['password' => $hashedPassword]);
        
        if ($this->db->affected_rows() > 0) {
            // Log activity
            $this->db->select('first_name, last_name');
            $this->db->where('id', $admin_id);
            $admin_query = $this->db->get('admin');
            $adminName = 'Unknown';
            if ($admin_query->num_rows() > 0) {
                $admin_data = $admin_query->row();
                $adminName = $admin_data->first_name . ' ' . $admin_data->last_name;
            }
            $desc = "Password reset for: " . $adminName;
            $this->genmod->addevent("Password Reset", $admin_id, $desc, "admin", $this->session->admin_id);
            
            $json = ['status' => 1, 'msg' => 'Password reset successfully'];
        } else {
            $json = ['status' => 0, 'msg' => 'Failed to reset password'];
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}
