<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/13/13
 * @Date Modified: 11/22/13
 * @Purpose: Used to load additional page stuff (like HTML headers and what not)
 * @Version: 1.0
 */

/**
 * USAGE:
 *  To use the model:
 *      Within your controller, use:
 *      $renderPage = $this->load_model('renderPage');
 *      $renderPage->parse('Page you want to load');
 */
class model_timeclock_renderPage {
    private $_user;

    /**
     * @Purpose: Loads a page, including the neccessary HTML headers and things
     */
    public function parse($page, $full_page=False) {
        global $system_di;
        $system_di->template->timeclock_root = $system_di->config->timeclock_root;
        $system_di->template->timeclock_assets = $system_di->config->timeclock_assets;
        
        $system_di->template->parse($system_di->config->timeclock_subdirectories . '_includes_htmlbegin');
        ($full_page) ? $system_di->template->parse($system_di->config->timeclock_subdirectories . '_includes_navbar') : '';
        $system_di->template->parse($system_di->config->timeclock_subdirectories . '_' . $page);
        $system_di->template->parse($system_di->config->timeclock_subdirectories . '_includes_htmlend');
    }
    
    /**
     * @Purpose: Used to generate pagination links
     */
    public function generate_pagination($page, $type='employees', $current_page = 1) {
        global $system_di;
        
        if (0 === $system_di->template->paginate_by) {
            return False;
        }
        
        $page = $system_di->config->timeclock_root . $page . '/';
        $current_page = (1 > $current_page) ? 1 : $current_page;
        $page_link = array('previous' => $current_page-1, 'next' => $current_page+1);
        $query_string = '';
        
        switch ($type) {
            case 'employees':
                $query_string = 'FROM `employees`';
                break;
            case 'payperiods':
                $query_string = 'FROM `pay_periods`';
                break;
            default:
                return false;
        }
        $query = $system_di->db->query("SELECT * {$query_string}");
        $last_page = (int) ceil((count($query) / $system_di->template->paginate_by));
        
        $system_di->template->previous = (1 === $current_page) ? '<li class="disabled"><a href="#">&laquo;</a></li>' : '<li><a href="' . $page . ($current_page-1) . '">&laquo;</a></li>';
        $system_di->template->next = ($last_page === $current_page) ? '<li class="disabled"><a href="#">&raquo;</a></li>' : '<li><a href="' . $page . ($current_page+1) . '">&raquo;</a></li>';
        
        $system_di->template->link1 = '';
        $system_di->template->link2 = '';
        $system_di->template->link3 = '';
        
        switch ($current_page) {
            case 2 >= $current_page: //Use case 1 where "1" "2" "3 ..."
                $system_di->template->link1 = (1 === $current_page) ? '<li class="active"><a href="#">1</a></li>' : '<li><a href="' . $page . '1">1</a></li>';
                if ($last_page >= 2) {
                    $system_di->template->link2 = (2 === $current_page) ? '<li class="active"><a href="#">2</a></li>' : '<li><a href="' . $page . '2">2</a></li>';
                }
                if ($last_page >= 3) {
                    $system_di->template->link3  = (3 === $last_page) ? '<li><a href="' . $page . '3">3</a></li>' : '<li><a href="' . $page . '3">3 ...</a></li>';
                }
                break;
            case $current_page >= ($last_page-2): //Use case 3 where "... 2" "3" "4"
                $system_di->template->link1 = (1 === ($page_link['previous'] - 1)) ? '<li><a href="' . $page . '1">1</a></li>' : '<li><a href="' . $page . ($page_link['previous']-1) . '">... ' . ($page_link['previous']-1) . '</a></li>';
                $system_di->template->link2 = '<li><a href="' . $page . $page_link['previous'] . '">' . $page_link['previous'] . '</a></li>';
                $system_di->template->link3 = '<li class="active"><a href="#">' . $current_page . '</a></li>';
                break;
            default: //Use casee where "... 2" "3" "4 ..."
                $system_di->template->link1 = '<li><a href="' . $page . $page_link['previous'] . '">' . $page_link['previous'] . ' ...</a></li>';
                $system_di->template->link2 = '<li class="active"><a href="#">' . $current_page . '</a></li>';
                $system_di->template->link3  = '<li><a href="' . $page . $page_link['next'] .'">' . $page_link['next'] . ' ...</a></li>';
        }
        
        return $system_di->template->parse($system_di->config->timeclock_subdirectories . '_pagination', true);
    }
}

//End File
