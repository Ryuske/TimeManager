<?php
/**
 * Author: Kenyon Haliwell
 * Date Created: 1/9/13
 * Date Modified: 1/24/14
 * Purpose: Used as a wrapper for various methods surrounding quotes
 */

global $sys;
$sys->router->load_helpers('interfaces', 'general', 'timemanager');
 
class model_timemanager_quotes implements general_actions {
    public $sheets;
    public $blanks;
    public $parts;
    
    public function __construct() {
        global $sys;
        $this->sys = &$sys;
        
        if (array_key_exists('update_quote', $_POST)) {
            $this->edit();
        }
    }
    
    /**
     * Purpose: Used to get the different $_POST fields
     */
    public function check_input($action) {
        $error = '';
        $this->quoted['time'] = array();
        $this->quoted['material'] = array();
        $this->actual['material'] = array();
        $this->quoted['outsource'] = array();
        $this->actual['outsource'] = array();
        $this->quoted['sheets'] = array();
        $this->actual['sheets'] = array();
        $this->quoted['blanks'] = array();
        $this->actual['blanks'] = array();
        $this->quoted['parts'] = array();
        $this->actual['parts'] = array();
        
        //name="quotes[time][x]"
        /*
         * array {
            'department_id'
            'hourly_value',
            'initial_time',
            'repeat_time'
         }
         */
        //name="quotes[material][material_id][x]
        // Prepare quoted information (time, material, etc) for storage into the database
        if (array_key_exists('quotes', $_POST) && is_array($_POST['quotes'])) {
            foreach ($_POST['quotes'] as $quote_type=>$quote_array) {
                switch ($quote_type) {
                    case 'time':
                        $this->quoted['time'][] = $quote_array;
                        break;
                    case 'material':
                        $this->quoted['material'] = $quote_array;
                        break;
                    case 'outsource':
                        $this->quoted['outsource'] = $quote_array;
                        break;
                    case 'sheets':
                        $this->quoted['sheets'] = $quote_array;
                        break;
                    case 'blanks':
                        $this->quoted['blanks'] = $quote_array;
                        break;
                    case 'parts':
                        $this->quoted['parts'] = $quote_array;
                        break;
                    default:
                        //Do nothing
                }
            }
        } //End quoted information
        
        // Prepare actual information (material, outsource, etc) for storage into the database
        if (array_key_exists('actuals', $_POST) && is_array($_POST['actuals'])) {
            foreach ($_POST['actuals'] as $actual_type=>$actual_array) {
                switch ($actual_type) {
                    case 'material':
                        $this->actual['material'] = $actual_array;
                        break;
                    case 'outsource':
                        $this->actual['outsource'] = $actual_array;
                        break;
                    case 'sheets':
                        $this->actual['sheets'] = $actual_array;
                        break;
                    case 'blanks':
                        $this->actual['blanks'] = $actual_array;
                        break;
                    case 'parts':
                        $this->actual['parts'] = $actual_array;
                        break;
                    default:
                        //Do nothing
                }
            }
        } //End actual information
        
        foreach ($this->quoted as &$quote) {
            $quote = json_encode($quote);
        }
        foreach ($this->actual as &$actual) {
            $actual = json_encode($actual);
        }
        
        switch ($action) {
            case 'add':
                if (!array_key_exists('job_id', $_POST) || '' === $_POST['job_id']) {
                    $error .= '<p>That job doesn\'t exist.</p>';
                }
                break;
            case 'edit':
                if (!array_key_exists('job_id', $_POST) || '' === $_POST['job_id']) {
                    $error .= '<p>That job doesn\'t exist.</p>';
                } else {
                    $job = $this->sys->db->query("SELECT `quote_id` FROM  `job_quotes` WHERE `job_id`=:job_id", array(
                            ':job_id' => substr($_POST['job_id'], 0, 256)
                    ));
                    
                    if (empty($job)) {
                        $error .= '<p>That job doesn\'t exist.</p>';
                    }
                }
                break;
            case 'remove':
                break;
            default:
                //Do nothing
        }
        
        if (!empty($error)) {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Purpose: Add quote to database
     */
    public function add() {
        $error = $this->check_input('add');
        
        if (!$error) {
            $this->sys->db->query("INSERT INTO `job_quotes` (
                `quote_id`, `job_id`, `quoted_time`, `quoted_material`, `actual_material`, `quoted_outsource`, `actual_outsource`,
                `quoted_sheets`, `actual_sheets`, `quoted_blanks`, `actual_blanks`, `quoted_parts`, `actual_parts`
            ) VALUES (
                NULL, :job_id, :quoted_time, :quoted_material, :actual_material, :quoted_outsource, :actual_outsource,
                :quoted_sheets, :actual_sheets, :quoted_blanks, :actual_blanks, :quoted_parts, :actual_parts
            )",
            array(
                ':job_id' => (int) substr($_POST['job_id'], 0, 10),
                ':quoted_time' => $this->quoted['time'],
                ':quoted_material' => $this->quoted['material'],
                ':actual_material' => $this->actual['material'],
                ':quoted_outsource' => $this->quoted['outsource'],
                ':actual_outsource' => $this->actual['outsource'],
                ':quoted_sheets' => $this->quoted['sheets'],
                ':actual_sheets' => $this->actual['sheets'],
                ':quoted_blanks' => $this->quoted['blanks'],
                ':actual_blanks' => $this->actual['blanks'],
                ':quoted_parts' => $this->quoted['parts'],
                ':actual_parts' => $this->actual['parts'],
            ));
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Purpose: Edit quotes on the database
     */
    public function edit() {
        $error = $this->check_input('edit');

        if (empty($error)) {
            $this->sys->db->query("
                UPDATE `job_quotes` SET
                    `quoted_time`=:quoted_time, `quoted_material`=:quoted_material, `actual_material`=:actual_material,
                    `quoted_outsource`=:quoted_outsource, `actual_outsource`=:actual_outsource,
                    `quoted_sheets`=:quoted_sheets, `actual_sheets`=:actual_sheets,
                    `quoted_blanks`=:quoted_blanks, `actual_blanks`=:actual_blanks,
                    `quoted_parts`=:quoted_parts, `actual_parts`=:actual_parts
                WHERE `job_id`=:job_id",
            array(
                ':job_id' => (int) substr($_POST['job_id'], 0, 10),
                ':quoted_time' => $this->quoted['time'],
                ':quoted_material' => $this->quoted['material'],
                ':actual_material' => $this->actual['material'],
                ':quoted_outsource' => $this->quoted['outsource'],
                ':actual_outsource' => $this->actual['outsource'],
                ':quoted_sheets' => $this->quoted['sheets'],
                ':actual_sheets' => $this->actual['sheets'],
                ':quoted_blanks' => $this->quoted['blanks'],
                ':actual_blanks' => $this->actual['blanks'],
                ':quoted_parts' => $this->quoted['parts'],
                ':actual_parts' => $this->actual['parts'],
            ));
            
            $this->sys->template->response = '<div class="form_success">Job Updated Successfully</div>';
            return true;
        }
        
        return false;
    }
    
    /**
     * Purpose: Not actually used, job_id removal cascades to quotes
     */
    public function remove() {}
    
    /**
     * Purpose: Used to return quotes
     */
    public function get($action, $paginate=false) {
        $quotes_query = $this->sys->db->query("
            SELECT * FROM
                `job_quotes` AS quotes
                    JOIN `jobs` AS jobs on jobs.job_uid=:job_id
                    JOIN `clients` AS client on client.client_id=jobs.client
            WHERE quotes.job_id=jobs.job_id", array(
            ':job_id' => (int) substr($action, 0, 256)
        ));

        $return_quote = array();
        
        foreach ($quotes_query as $quote ) {
            
            $return_quote['quoted_time'] = json_decode($quote['quoted_time']);
            $return_quote['quoted_time'] = (array_key_exists(0, $return_quote['quoted_time'])) ? $return_quote['quoted_time'][0] : array();
            
            $return_quote['quoted_material'] = json_decode($quote['quoted_material']);
            $return_quote['actual_material'] = json_decode($quote['actual_material']);
            
            $return_quote['quoted_outsource'] = json_decode($quote['quoted_outsource']);
            $return_quote['actual_outsource'] = json_decode($quote['actual_outsource']);
            
            $return_quote['quoted_sheets'] = json_decode($quote['quoted_sheets']);
            $return_quote['actual_sheets'] = json_decode($quote['actual_sheets']);
            
            $return_quote['quoted_blanks'] = json_decode($quote['quoted_blanks']);
            $return_quote['actual_blanks'] = json_decode($quote['actual_blanks']);
            
            $return_quote['quoted_parts'] = json_decode($quote['quoted_parts']);
            $return_quote['actual_parts'] = json_decode($quote['actual_parts']);
        }
        
        $return_quote = array(
            'job' => array(
                'job_id'            => $quotes_query[0]['job_id'],
                'job_uid'           => $quotes_query[0]['job_uid'],
                'job_name'          => $quotes_query[0]['job_name'],
                'client'            => $quotes_query[0]['client'],
                'status'            => $quotes_query[0]['status'],
                'job_quantity'      => $quotes_query[0]['job_quantity'],
                'job_start_date'    => $quotes_query[0]['job_start_date'],
                'job_due_date'      => $quotes_query[0]['job_due_date'],
                'client_id'         => $quotes_query[0]['client_id'],
                'client_name'       => $quotes_query[0]['client_name']
            ),
            'quote'     => $return_quote,
            'max_ids'   => array(
                'quoted_material' => $this->find_max_id($return_quote['quoted_material']),
                'actual_material' => $this->find_max_id($return_quote['actual_material']),
                'quoted_outsource' => $this->find_max_id($return_quote['quoted_outsource']),
                'actual_outsource' => $this->find_max_id($return_quote['actual_outsource']),
                'quoted_sheets' => $this->find_max_id($return_quote['quoted_sheets']),
                'actual_sheets' => $this->find_max_id($return_quote['actual_sheets']),
                'quoted_blanks' => $this->find_max_id($return_quote['quoted_blanks']),
                'actual_blanks' => $this->find_max_id($return_quote['actual_blanks']),
                'quoted_parts' => $this->find_max_id($return_quote['quoted_parts']),
                'actual_parts' => $this->find_max_id($return_quote['actual_parts'])
            )
        );

        return $return_quote;
    }
    
    /**
     * Purpose: Used to get all the information about time quotes
     */
    public function get_time_quote($departments, $quote) {
        $quoted_time = $quote['quote']['quoted_time'];
        $return_time = array('total_time' => 0, 'total_cost' => 0);
        
        foreach ($departments as $department) {
            $time_quote = (is_object($quoted_time)) ? $quoted_time->$department['department_id'] : 0;
            $hourly_value = ((is_object($time_quote) || is_array($time_quote)) && array_key_exists('hourly_value', $time_quote)) ? $time_quote->hourly_value : $department['charged_hourly_value'];
            $initial_time = ((is_object($time_quote) || is_array($time_quote)) && array_key_exists('initial_time', $time_quote)) ? $time_quote->initial_time : 0;
            $repeat_time = ((is_object($time_quote) || is_array($time_quote)) && array_key_exists('repeat_time', $time_quote)) ? $time_quote->repeat_time : 0;
            $initial_cost = ($initial_time * $hourly_value);
            $total_time = $initial_time + ($repeat_time * $quote['job']['job_quantity']);
            $total_individual_cost = ($repeat_time * $hourly_value * $quote['job']['job_quantity']);
            $total_cost = (0 != $total_individual_cost) ? ($initial_cost + $total_individual_cost) : $initial_cost;
            
            $return_time[$department['department_id']] = array(
                'department_id'             => $department['department_id'],
                'department_name'           => $department['department_name'],
                'hourly_value'              => number_format(round($hourly_value, 2), 2),
                'initial_time'              => number_format(round($initial_time, 2), 2),
                'initial_cost'              => number_format(round(($initial_cost), 2), 2),
                'repeat_time'               => number_format(round($repeat_time, 2), 2),
                'repeat_cost'               => number_format(round(($repeat_time * $hourly_value), 2), 2),
                'total_time'                => number_format(round($total_time, 2), 2),
                'total_individual_cost'     => round($total_individual_cost, 2),
                'total_cost'                => number_format(round($total_cost, 2), 2)
            );
            
            $return_time['total_time'] += round($total_time);
            $return_time['total_cost'] += round($total_cost);
        }
        
        return $return_time;
    }
    
    /**
     * Purpose: Used to get information about materials
     */
    public function get_material($quote, $array_to_use) {
        if ('quoted' === $array_to_use) {
            $return_material = array('quoted_total' => 0, 'original_total' => 0);
        } elseif ('actual' === $array_to_use) {
            $return_material = array('total_cost' => 0);
        }
        
        if (!empty($quote)) {
            foreach ($quote as $key=>$material) {
                if ('quoted' == $array_to_use) {
                    $return_material[$key] = array(
                        'material_id'           => $key,
                        'description'           => $material->description,
                        'vendor'                => $material->vendor,
                        'individual_quantity'   => $material->individual_quantity,
                        'cost'                  => number_format(round($material->cost, 2), 2),
                        'markup'                => $material->markup
                    );
                    
                    $return_material[$key]['total_quantity']    = ($return_material[$key]['individual_quantity'] * $this->sys->template->quote['job']['job_quantity']);
                    $return_material[$key]['total_cost']        = number_format(round(($return_material[$key]['total_quantity'] * $return_material[$key]['cost'] * (($return_material[$key]['markup'] * 0.01)+1)), 2), 2);
                    $return_material['quoted_total']            += $return_material[$key]['total_cost'];
                    $return_material['original_total']          += round($return_material[$key]['total_quantity'] * $return_material[$key]['cost'], 2);
                } elseif ('actual' === $array_to_use) {
                    $return_material[$key] = array(
                        'material_id'           => $key,
                        'description'           => $material->description,
                        'vendor'                => $material->vendor,
                        'individual_quantity'   => $material->individual_quantity,
                        'cost'                  => number_format(round($material->cost, 2), 2),
                        'po'                    => $material->po,
                        'delivery_date'         => $material->delivery_date
                    );
                    
                    $return_material[$key]['total_quantity']    = ($return_material[$key]['individual_quantity'] * $this->sys->template->quote['job']['job_quantity']);
                    $return_material[$key]['total_cost']        = number_format(round($return_material[$key]['total_quantity'] * $return_material[$key]['cost'], 2), 2);
                    $return_material['total_cost']              += round($return_material[$key]['total_quantity'] * $return_material[$key]['cost'], 2);
                }
            }
        }
        
        return $return_material;
    }
    
    /**
     * Purpose: Used to get information about outsourced tasks
     */
    public function get_outsource($quote, $array_to_use) {
        if ('quoted' === $array_to_use) {
            $return_outsource = array('quoted_total' => 0, 'original_total' => 0);
        } elseif ('actual' === $array_to_use) {
            $return_outsource = array('total_cost' => 0);
        }
        
        if (!empty($quote)) {
            foreach ($quote as $key=>$outsource) {
                if ('quoted' == $array_to_use) {
                    $return_outsource[$key] = array(
                        'outsource_id'  => $key,
                        'process'       => $outsource->process,
                        'company'       => $outsource->company,
                        'quantity'      => $outsource->quantity,
                        'cost'          => number_format(round($outsource->cost, 2), 2),
                        'markup'        => $outsource->markup
                    );
                    
                    $return_outsource[$key]['total_cost']   = number_format(round(($return_outsource[$key]['quantity'] * $return_outsource[$key]['cost'] * (($return_outsource[$key]['markup'] * 0.01)+1)), 2), 2);
                    $return_outsource['quoted_total']       += $return_outsource[$key]['total_cost'];
                    $return_outsource['original_total']     += round($return_outsource[$key]['quantity'] * $return_outsource[$key]['cost'], 2);
                } elseif ('actual' === $array_to_use) {
                    $return_outsource[$key] = array(
                        'outsource_id'  => $key,
                        'process'       => $outsource->process,
                        'company'       => $outsource->company,
                        'quantity'      => $outsource->quantity,
                        'cost'          => number_format(round($outsource->cost, 2), 2),
                        'po'            => $outsource->po,
                        'delivery_date' => $outsource->delivery_date
                    );
                    
                    $return_outsource[$key]['total_cost']   = number_format(round($return_outsource[$key]['quantity'] * $return_outsource[$key]['cost'], 2), 2);
                    $return_outsource['total_cost']         += round($return_outsource[$key]['quantity'] * $return_outsource[$key]['cost'], 2);
                }
            }
        }
        
        return $return_outsource;
    }
    
    /**
     * Purpose: Used to get information about sheets, blanks and parts
     */
    public function get_information($quote) {
        $this->sheets = array(
            'quoted' => $this->get_sheets($quote['quoted_sheets'], 'quoted'),
            'actual' => $this->get_sheets($quote['actual_sheets'], 'actual')
        );

        $this->blanks = array(
            'quoted' => $this->get_blanks($quote['quoted_blanks'], 'quoted'),
            'actual' => $this->get_blanks($quote['actual_blanks'], 'actual')
        );

        $this->parts = array(
            'quoted' => $this->get_parts($quote['quoted_parts'], 'quoted'),
            'actual' => $this->get_parts($quote['actual_parts'], 'actual')
        );
    }
    
    public function get_shared_data($option, $id, $type='quoted') {
        if (!in_array($type, array('quoted', 'actual'))) {
            return 0;
        }
        
        switch ($option) {
            case 'sheets_required':
                $total_blanks = 0;
                $blanks_sheet = 0;

                foreach ($this->blanks[$type] as $blank) {
                    if ($blank['sheet_id'] === $id) {
                        $total_blanks += $this->get_shared_data('blanks_required', $blank['blank_id']);
                        $blanks_sheet += $blank['blanks_sheet'];
                    }
                }
                
                $response = ($blanks_sheet > 0) ? $total_blanks / $blanks_sheet : 0;
                break;
            case 'sheets_total_cost': //Total of all the sheets required
                $total_cost = 0;
                
                foreach ($this->sheets[$type] as $sheet) {
                    //Had to come up with a way to get rid of the need for markup when finding the actual total of quoted sheets
                    $markup = ('actual' === $type || 1 === $id) ? 1 : ($sheet['markup'] * 0.01)+1;
                    $total_cost += $this->get_shared_data('sheet_total_cost', $sheet['sheet_id'], $type) * $markup;
                }
                
                return number_format(round($total_cost, 2), 2);
                break;
            case 'sheet_total_cost': //Total of the required number of specifics sheet
                $response = number_format(round(($this->get_shared_data('sheets_required', $id, $type) * $this->sheets[$type][$id]['cost_sheet']), 2), 2);
                break;
            case 'sheet_quoted_cost': //Total of the required number of specifics sheet plus markup
                $response = number_format(round((
                    $this->get_shared_data('sheets_required', $id) * $this->sheets['quoted'][$id]['cost_sheet'] * (($this->sheets['quoted'][$id]['markup'] * 0.01)+1)
                ), 2), 2);
                break;
            case 'blanks_minimum':
                $parts_blank = 0;
                $parts_assembly = 0;
                
                foreach ($this->parts[$type] as $part) {
                    if ($part['blank_id'] === $id) {
                        $parts_blank += $part['parts_blank'];
                        $parts_assembly += $part['parts_assembly'];
                    }
                }
                
                $response = ($parts_blank * $parts_assembly) / $this->sys->template->quote['job']['job_quantity'];
                break;
            case 'blanks_required':
                $response = $blanks_minimum = $this->get_shared_data('blanks_minimum', $id, $type); + 0.5;
                break;
            case 'sheet_usage':
                $blanks_required = 0;
                
                foreach ($this->blanks[$type] as $blank) {
                    if ($blank['sheet_id'] === $id) {
                        $blanks_required += $blank['blanks_required'];
                    }
                }
                
                $response = $blanks_required / $this->blanks[$type][$id]['blanks_sheet'];
                break;
            case 'blanks_total_cost':
                $response = number_format(round(($this->get_shared_data('blanks_required', $id, $type) * $this->get_shared_data('cost_blank', $id, $type)), 2), 2);
                break;
            case 'cost_blank':
                $response = number_format(round($this->sheets[$type][$this->blanks[$type][$id]['sheet_id']]['cost_lb'] * $this->blanks[$type][$id]['lbs_blank'], 2), 2);
                break;
            case 'parts_sheet':
                $response = $this->blanks[$type][$this->parts[$type][$id]['blank_id']]['blanks_sheet'] * $this->parts[$type][$id]['parts_blank'];
                break;
            case 'parts_total_cost':
                $response = number_format(round(($this->get_shared_data('cost_blank', $this->parts[$type][$id]['blank_id'], $type) / $this->parts[$type][$id]['parts_blank']), 2), 2);
                break;
            default:
                $response = 'Undefined Operation';
        }
        
        return $response;
    }
    
    /**
     * Purpose: Used to get information about sheets
     */
    public function get_sheets($quote, $array_to_use) {
        $return_sheet = array();
        
        if (!empty($quote)) {
            foreach ($quote as $key=>$sheet) {
                if ('quoted' == $array_to_use) {
                    $return_sheet[$key] = array(
                        'sheet_id'          => $key,
                        'material'          => $sheet->material,
                        'vendor'            => $sheet->vendor,
                        'size'              => $sheet->size,
                        'lbs_sheet'         => $sheet->lbs_sheet,
                        'cost_lb'           => number_format(round($sheet->cost_lb, 2), 2),
                        'markup'            => $sheet->markup,
                        'cost_sheet'        => number_format(round($sheet->cost_lb * $sheet->lbs_sheet, 2), 2)
                    );
                } elseif ('actual' === $array_to_use) {
                    $return_sheet[$key] = array(
                        'sheet_id'          => $key,
                        'material'          => $sheet->material,
                        'vendor'            => $sheet->vendor,
                        'size'              => $sheet->size,
                        'lbs_sheet'         => $sheet->lbs_sheet,
                        'cost_lb'           => number_format(round($sheet->cost_lb, 2), 2),
                        'cost_sheet'        => number_format(round($sheet->cost_lb * $sheet->lbs_sheet, 2), 2)
                    );
                }
            }
        }
        
        return $return_sheet;
    }
    
    /**
     * Purpose: Used to get information about blanks
     */
    public function get_blanks($quote, $array_to_use) {
        $return_blanks = array();
        
        if (!empty($quote)) {
            foreach ($quote as $key=>$blank) {
                if ('quoted' == $array_to_use) {
                    $return_blanks[$key] = array(
                        'blank_id'         => $key,
                        'sheet_id'          => $blank->sheet_id,
                        'size'              => $blank->size,
                        'blanks_sheet'      => $blank->blanks_sheet,
                        'lbs_blank'         => $blank->lbs_blank
                    );
                } elseif ('actual' === $array_to_use) {
                    $return_blanks[$key] = array(
                        'blank_id'         => $key,
                        'sheet_id'          => $blank->sheet_id,
                        'size'              => $blank->size,
                        'blanks_sheet'      => $blank->blanks_sheet,
                        'lbs_blank'         => $blank->lbs_blank
                    );
                }
            }
        }
        
        return $return_blanks;
    }
    
    /**
     * Purpose: Used to get information about parts
     */
    public function get_parts($quote, $array_to_use) {
        $return_parts = array();
        
        if (!empty($quote)) {
            foreach ($quote as $key=>$part) {
                if ('quoted' == $array_to_use) {
                    $return_parts[$key] = array(
                        'part_id'           => $key,
                        'blank_id'          => $part->blank_id,
                        'description'       => $part->description,
                        'size'              => $part->size,
                        'parts_assembly'    => $part->parts_assembly,
                        'parts_blank'       => $part->parts_blank,
                    );
                } elseif ('actual' === $array_to_use) {
                    $return_parts[$key] = array(
                        'part_id'           => $key,
                        'blank_id'          => $part->blank_id,
                        'description'       => $part->description,
                        'size'              => $part->size,
                        'parts_assembly'    => $part->parts_assembly,
                        'parts_blank'       => $part->parts_blank,
                    );
                }
            }
        }
        
        return $return_parts;
    }
    
    /**
     * Purpose: Used to find the highest value id
     */
    public function find_max_id($array) {
        $max_id = 0;
        
        foreach ($array as $key=>$item) {
            $max_id = ($max_id < $key) ? $key : $max_id;
        }
        
        return $max_id;
    }
}