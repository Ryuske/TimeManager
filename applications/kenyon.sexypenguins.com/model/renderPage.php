<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 11/13/13
 * @Date Modified: 11/13/13
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
class model_renderPage {
    private $_user;

    /**
     * @Purpose: Loads a page, including the neccessary HTML headers and things
     * @Access: Public
     */
    public function parse($page, $full_page=False) {
        global $system_di;
        $system_di->template->timeclock_root = $system_di->config->timeclock_root;

        $system_di->template->parse('includes_htmlbegin');
        ($full_page) ? $system_di->template->parse('includes_navbar') : '';
        $system_di->template->parse($page);
        $system_di->template->parse('includes_htmlend');
    } //end parse
}//End model_renderPage

//End File
