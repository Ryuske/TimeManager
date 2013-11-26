<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/13/13
 * @Date Modified: 11/26/13
 * @Purpose: Used to load additional page stuff (like HTML headers and what not)
 * @Version: 1.0
 */

/**
 * USAGE:
 *  To use the model:
 *      Within your controller, use:
 *      $renderPage = $this->load_model('renderPage');
 *      $renderPage->parse('main', True);
 *          This would load timeclock main, and it would include the HTML header/footers which contains the navbar
 *
 *      $renderPage->parse('login', False);
 *          This would load timeclock login, without the HTML headers and footers so that it is a little more basic page
 *
 *      $employees_pagination = $renderPage->generate_pagination('main', 'employees', 1);
 *          Parameter 1 is the page that prefixes the links (i.e. main/1 main/2 etc)
 *          Parameter 2 is what you want to paginate
 *          Parameter 3 is the page you're currently looking at
 *          Echo'ing this out would add pagination links <previous> 1, 2, 3 <next> based on employees and the page you're on
 *          
 *      $pay_period_pagination = $renderPage->generate_pagination('employee/view/<id>/<payperiod>', 'payperiods', 1);
 *          Parameter 1 is the page that prefixes the links (i.e. main/1 main/2 etc)
 *          Parameter 2 is what you want to paginate
 *          Parameter 3 is the page you're currently looking at
 *          Echo'ing this out would add pagination links <previous> 1, 2, 3 <next> based on payperiods and the page you're on
 *          Payperiods are still created as 4 arrays, so if you were to paginate by 1, it would still return 4 pay periods
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
        $page = strtolower($page);
        $type = strtolower($type);
        
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
                $system_di->template->paginate_by *= 4;
                break;
            default:
                return false;
        }
        $query = $system_di->db->query("SELECT * $query_string");
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
    } //End generate_pagination
}

//End File
