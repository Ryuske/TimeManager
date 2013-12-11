<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 12/10/13
 * @Date Modified: 12/11/13
 * @Purpose: Used as a wrapper for various methods surrounding employee categories
 * @Version: 2.0
 */

 class model_timeclock_categories {
    public function __construct() {
        global $sys;
        $this->sys = &$sys;
        
        if (array_key_exists('add_category', $_POST)) {
            $this->add();
        }
        if (array_key_exists('edit_category', $_POST)) {
            $this->edit();
        }
        if (array_key_exists('remove_category', $_POST)) {
            $this->remove();
        }
    }
    
    /**
     * Database Modification - Add/Edit/Remove
     */
    /**
     * @Purpose: Used to add categories to the database
     */
    protected function add() {
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
     * @Purpose: Used to edit categories in the database
     */
    protected function edit() {
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
     * @Purpose: Used to remove categories from the database
     */
    protected function remove() {
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
     * @Purpose: Returns a list of all the categories.
     */
    public function get_categories() {
        $categories = $this->sys->db->query("SELECT * FROM `categories`");
        
        return $categories;
    }
 }
 
 //End file
 