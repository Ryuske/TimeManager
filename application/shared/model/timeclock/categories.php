<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 12/10/13
 * Date Modified: 12/18/13
 * Purpose: Used as a wrapper for various methods surrounding employee categories
 * Version: 2.0
 */

global $sys;
$sys->router->load_helpers('interfaces', 'general', 'timeclock');
 
 class model_timeclock_categories implements general_actions {
    public function __construct() {
        global $sys;
        $this->sys = &$sys;
        
        $is_admin = $this->sys->db->query("SELECT `employee_role` FROM `employees` WHERE `employee_id`=:id", array(
            ':id' => (int) $this->sys->session['user']
        ));
        
        if (!empty($is_admin)) {
            $is_admin = ('admin' === $is_admin[0]['employee_role']) ? true : false;
        } else {
            $is_admin = false;
        }
        
        if (array_key_exists('add_category', $_POST) && $is_admin) {
            $this->add();
        }
        if (array_key_exists('edit_category', $_POST) && $is_admin) {
            $this->edit();
        }
        if (array_key_exists('remove_category', $_POST) && $is_admin) {
            $this->remove();
        }
    }
    
    /**
     * Database Modification - Add/Edit/Remove
     */
    /**
     * Purpose: Used to add categories to the database
     */
    public function add() {
        $error = (array_key_exists('category_name', $_POST) && '' === $_POST['category_name']) ? '<p>Please enter a name for the category</p>' : '';
        
        $add_category = ('' === $error) ? $this->sys->db->query("INSERT INTO `categories` (`category_id`, `category_name`) VALUES (NULL, :name)", array(
            ':name' => $_POST['category_name']
        )) : '';
        
        if (!empty($error)) {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
            return false;
        } else {
            $this->sys->template->response = '<div class="form_success">Category Added Successfully</div>';
            return true;
        }
    }
    
    /**
     * Purpose: Used to edit categories in the database
     */
    public function edit() {
        $error = '';
        
        if (!array_key_exists('category_id', $_POST)) {
            $error = '<p>Something wrong with the category ID.</p>';
        }
        if (!array_key_exists('category_name', $_POST) || '' === $_POST['category_name']) {
            $error .= '<p>Please enter a category name.</p>';
        }
        
        $category = ('' === $error) ? $this->sys->db->query("SELECT `category_id` FROM `categories` WHERE `category_id`=:id", array(
            ':id' => (int) $_POST['category_id']
        )) : '';
        
        if (empty($error) && empty($category)) {
            $error .= '<p>Category Doesn\'t Exist.</p>';
        } elseif (empty($error)) {
            $this->sys->db->query("UPDATE `categories` SET `category_name`=:name WHERE `category_id`=:id", array(
                ':name' => $_POST['category_name'],
                ':id'   => (int) $_POST['category_id']
            ));
        }
        
        if (!empty($error)) {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
            return false;
        } else {
            $this->sys->template->response = '<div class="form_success">Category Updated Successfully</div>';
            return true;
        }
    }
    
    /**
     * Purpose: Used to remove categories from the database
     */
    public function remove() {
        $error = '';
        
        if (!array_key_exists('category_id', $_POST)) {
            $error = '<p>Something wrong with the category ID.</p>';
        }
        
        if (empty($error)) {
            $this->sys->db->query("DELETE FROM `categories` WHERE `category_id`=:id", array(
                ':id' => (int) $_POST['category_id']
            ));
            
            $this->sys->template->response = '<div class="form_success">Category Removed Successfully</div>';
            return true;
        } else {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
            return false;
        }
    }
    /**
     * END: Database Modification Block
     */
    
    /**
     * Purpose: Returns a list of all the categories.
     */
    public function get($action=false, $pagination=false) {
        $categories = $this->sys->db->query("SELECT * FROM `categories`");
        $return_categories = array();
        
        if ($action) {
            foreach ($categories as $category) {
                $return_categories[$category['category_id']] = $category;
            }
        } else {
            $return_categories = $categories;
        }
        
        return $return_categories;
    }
 }
 
 //End file
 