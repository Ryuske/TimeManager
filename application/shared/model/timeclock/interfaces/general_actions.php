<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 12/18/13
 * Date Modified: 12/18/13
 * Purpose: An interfaces for general actions (add/edit/remove/get)
 * Version: 2.0
 */

 interface general_actions {
    public function add();
    public function edit();
    public function remove();
    public function get($action, $paginate);
 }
 
 //End file
 