<?php
defined('BASEPATH') OR exit('Access Denied');

/**
 * Permissions Library
 * Provides fine-grained access control for different user roles
 */
class Permissions {
    protected $CI;
    
    // Define role permissions
    private $role_permissions = [
        'Admin' => [
            'dashboard' => ['view', 'export'],
            'items' => ['view', 'add', 'edit', 'delete', 'import', 'export', 'bulk_update', 'bulk_delete'],
            'transactions' => ['view', 'create', 'edit', 'delete', 'cancel', 'export'],
            'reports' => ['view', 'export'],
            'customers' => ['view', 'add', 'edit', 'delete', 'import', 'export'],
            'staff' => ['view', 'add', 'edit', 'delete', 'suspend'],
            'settings' => ['view', 'edit'],
            'admin_management' => ['view', 'add', 'edit', 'delete'],
        ],
        'Manager' => [
            'dashboard' => ['view', 'export'],
            'items' => ['view', 'add', 'edit', 'import', 'export', 'bulk_update'],
            'transactions' => ['view', 'create', 'edit', 'cancel', 'export'],
            'reports' => ['view', 'export'],
            'customers' => ['view', 'add', 'edit', 'import', 'export'],
            'staff' => ['view', 'add', 'edit'],
            'settings' => ['view'],
        ],
        'Cashier' => [
            'dashboard' => ['view'],
            'items' => ['view'],
            'transactions' => ['view', 'create'],
            'reports' => ['view'],
            'customers' => ['view', 'add'],
        ],
        'Inventory Clerk' => [
            'dashboard' => ['view'],
            'items' => ['view', 'add', 'edit', 'import', 'export', 'bulk_update'],
            'transactions' => ['view'],
            'reports' => ['view'],
        ],
        'Super' => [
            // Super admin has all permissions
            'all' => ['all'],
        ],
        'Basic' => [
            'transactions' => ['view', 'create'],
        ],
    ];
    
    public function __construct() {
        $this->CI = &get_instance();
    }
    
    /**
     * Check if current user has permission for a module and action
     * @param string $module Module name (e.g., 'items', 'transactions')
     * @param string $action Action name (e.g., 'add', 'edit', 'delete')
     * @return bool
     */
    public function hasPermission($module, $action = 'view') {
        $user_role = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'Basic';
        
        // Super admin has all permissions
        if ($user_role === 'Super') {
            return true;
        }
        
        // Check if role exists in permissions
        if (!isset($this->role_permissions[$user_role])) {
            return false;
        }
        
        $permissions = $this->role_permissions[$user_role];
        
        // Check for module permissions
        if (isset($permissions[$module])) {
            return in_array($action, $permissions[$module]) || in_array('all', $permissions[$module]);
        }
        
        return false;
    }
    
    /**
     * Check if user can access a module at all
     * @param string $module
     * @return bool
     */
    public function canAccess($module) {
        return $this->hasPermission($module, 'view');
    }
    
    /**
     * Require permission or redirect
     * @param string $module
     * @param string $action
     */
    public function requirePermission($module, $action = 'view') {
        if (!$this->hasPermission($module, $action)) {
            redirect(base_url('dashboard'));
        }
    }
    
    /**
     * Get all permissions for current user
     * @return array
     */
    public function getUserPermissions() {
        $user_role = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'Basic';
        
        if ($user_role === 'Super') {
            return ['all' => ['all']];
        }
        
        return isset($this->role_permissions[$user_role]) ? $this->role_permissions[$user_role] : [];
    }
    
    /**
     * Check if user role matches
     * @param string|array $roles
     * @return bool
     */
    public function isRole($roles) {
        $user_role = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : 'Basic';
        
        if (is_array($roles)) {
            return in_array($user_role, $roles);
        }
        
        return $user_role === $roles;
    }
}

