<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 11/13/13
 * Date Modified: 12/31/13
 * Purpose: Used to load additional page stuff (like HTML headers and what not)
 */

class model_timemanager_renderPage {
    private $_user;

    /**
     * @Purpose: Loads a page, including the neccessary HTML headers and things
     */
    public function parse($page, $full_page=False) {
        global $sys;
        $sys->template->timemanager_root = $sys->config->timemanager_root;
        $sys->template->timemanager_assets = $sys->config->timemanager_assets;
        
        $sys->template->page = $sys->template->parse($sys->config->timemanager_subdirectories . '_' . $page, true);
        if ($sys->template->admin) {
            $sys->template->navbar = ($full_page) ? $sys->template->parse($sys->config->timemanager_subdirectories . '_includes_navbar-admin', true) : '';
        } else {
            $sys->template->navbar = ($full_page) ? $sys->template->parse($sys->config->timemanager_subdirectories . '_includes_navbar', true) : '';
        }
        $sys->template->parse($sys->config->timemanager_subdirectories . '_includes_htmlwrapper');
    }
    
    /**
     * Purpose: Used to generate pagination links
     */
    public function generate_pagination($page, $type='employees', $current_page = 1) {
        global $sys;
        $page = strtolower($page);
        $type = strtolower($type);
        
        if (0 === $sys->template->paginate_by) {
            return False;
        }
        
        $page = $sys->config->timemanager_root . $page . '/';
        $current_page = (1 > $current_page) ? 1 : $current_page;
        $page_link = array('previous' => $current_page-1, 'next' => $current_page+1);
        $query_string = '';
        
        switch ($type) {
            case 'employees':
                $table = 'employees';
                break;
            case 'jobs':
                $table = 'jobs';
                break;
            case 'payperiods':
                $table = 'pay_periods';
                $sys->template->paginate_by *= 4;
                break;
            default:
                return false;
        }
        $query = $sys->db->query("SELECT * FROM `$table`");
        $sys->template->paginate_by = (0 == $sys->template->paginate_by) ? 1 : $sys->template->paginate_by;
        $last_page = (int) ceil((count($query) / $sys->template->paginate_by));
        
        $sys->template->previous = (1 === $current_page) ? '<li class="disabled"><a href="#">&laquo;</a></li>' : '<li><a href="' . $page . ($current_page-1) . '">&laquo;</a></li>';
        $sys->template->next = ($last_page === $current_page) ? '<li class="disabled"><a href="#">&raquo;</a></li>' : '<li><a href="' . $page . ($current_page+1) . '">&raquo;</a></li>';
        
        $sys->template->link1 = '';
        $sys->template->link2 = '';
        $sys->template->link3 = '';
        
        switch ($current_page) {
            case 2 >= $current_page: //Use case 1 where "1" "2" "3 ..."
                $sys->template->link1 = (1 === $current_page) ? '<li class="active"><a href="#">1</a></li>' : '<li><a href="' . $page . '1">1</a></li>';
                if ($last_page >= 2) {
                    $sys->template->link2 = (2 === $current_page) ? '<li class="active"><a href="#">2</a></li>' : '<li><a href="' . $page . '2">2</a></li>';
                }
                if ($last_page >= 3) {
                    $sys->template->link3  = (3 === $last_page) ? '<li><a href="' . $page . '3">3</a></li>' : '<li><a href="' . $page . '3">3 ...</a></li>';
                }
                break;
            case $current_page == ($last_page-1): //Use case 3 where "... 2" "3" "4"
                $sys->template->link1 = (1 === ($page_link['previous'] - 1)) ? '<li><a href="' . $page . '1">1</a></li>' : '<li><a href="' . $page . ($page_link['previous']-1) . '">... ' . ($page_link['previous']-1) . '</a></li>';
                $sys->template->link2 = '<li class="active"><a href="#">' . $current_page . '</a></li>';
                $sys->template->link3  = '<li><a href="' . $page . $page_link['next'] .'">' . $page_link['next'] . '</a></li>';
                break;
            case $current_page == $last_page:
                $sys->template->link1 = (1 === ($page_link['previous'] - 1)) ? '<li><a href="' . $page . '1">1</a></li>' : '<li><a href="' . $page . ($page_link['previous']-1) . '">... ' . ($page_link['previous']-1) . '</a></li>';
                $sys->template->link2 = '<li><a href="' . $page . $page_link['previous'] . '">' . $page_link['previous'] . '</a></li>';
                $sys->template->link3 = '<li class="active"><a href="#">' . $current_page . '</a></li>';
                break;
            default: //Use casee where "... 2" "3" "4 ..."
                $sys->template->link1 = '<li><a href="' . $page . $page_link['previous'] . '">... ' . $page_link['previous'] . '</a></li>';
                $sys->template->link2 = '<li class="active"><a href="#">' . $current_page . '</a></li>';
                $sys->template->link3  = '<li><a href="' . $page . $page_link['next'] .'">' . $page_link['next'] . ' ...</a></li>';
        }
        
        return $sys->template->parse($sys->config->timemanager_subdirectories . '_pagination', true);
    } //End generate_pagination
}

//End File
