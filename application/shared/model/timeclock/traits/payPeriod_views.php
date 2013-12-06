<?php
trait payPeriod_views {
    /**
     * @Purpose: Used to get the response of add_date() - primarily used for if add_date() has an error
     */
    public function add_date_response() {
        return $this->_add_date_response;
    }
    
    /**
     * @Purpose: Used to figure out which times should be editable, and have an onClick attribute
     */
    protected function is_time_editable($hour, $times_array, $time_index, $time_operation, $date) {
        $return = '';
        if (array_key_exists($time_index, $hour[$time_operation]) && 0 != $hour[$time_operation][$time_index]) {
            $return = '<td onclick="updateTime(\'' . $date . '\', ' . $time_index . ', \'' . $time_operation . '\')">' . date($this->_timeFormat, $hour[$time_operation][$time_index]) . '</td>';
        } elseif (array_key_exists(($time_index - 1), $hour[$time_operation]) &&  0 != $hour[$time_operation][$time_index-1] || 0 === $time_index) { //Check if previous times are in
            $return = '<td onclick="updateTime(\'' . $date . '\', ' . $time_index . ', \'' . $time_operation . '\')"></td>';
        } else {
            $return = '<td></td>';
        }
        
        return $return;
    }
    
    /**
     * @Purpose: Used to create the table that is seen in the view.
     */
    public function generate_pay_period_table($employee_id, $pay_period) {
        $hours = $this->get_hours($employee_id, $pay_period);
        $return = '';
        
        if (!is_bool($hours)) {
            foreach ($hours as $date=>$hour) {
                $table = array();
                $iterations = (count($hour['in']) > count($hour['out'])) ? count($hour['in']) : count($hour['out']);
                $iterations = ($iterations >= 4) ? $iterations : 3;
                $i_tracker = 0;
                $multiplier = 0;
                
                for ($i=0; $i<$iterations; $i++) {
                    if ($i_tracker>2) {
                        $multiplier++;
                        $i_tracker = -1;
                    }
                    
                    $table[$multiplier]['in']['date'] = $date;
                    $table[$multiplier]['in'][] = $this->is_time_editable($hour, $table, $i, 'in', $date);
                    $table[$multiplier]['out']['date'] = $date;
                    $table[$multiplier]['out'][] = $this->is_time_editable($hour, $table, $i, 'out', $date);
                    
                    $i_tracker++;
                }
                
                foreach ($table as &$row) {
                    $in_count = count($row['in']);
                    $out_count = count($row['out']);
                    
                    for ($i=$in_count; $i<4; $i++) {
                        $row['in'][$i-1] = '<td></td>';
                    }
                    for ($i=$out_count; $i<4; $i++) {
                        $row['out'][$i-1] = '<td></td>';
                    }
                    
                    $return .= '<tr>';
                    $return .= '<td>' . $row['in']['date'] . '</td>';
                    
                    for ($i=0; $i<3; $i++) {
                        $return .= $row['in'][$i];
                        $return .= $row['out'][$i];
                    }
                    
                    $return .= '<td>' . $hour['total_hours'] . '</td>';
                    $return .= '</tr>';
                    
                    $hour['total_hours'] = 'Continuation of ' . $row['in']['date'];
                }
            }
        }
        
        return $return;
    } //End generate_current_pay_period_table
    
    /**
     * @Purpose: Used to create the table that displays all the previous pay periods
     */
    public function generate_previous_pay_periods_table($employee_id, $selected_pay_period) {
        $pay_periods = $this->get_pay_period('all', True);
        $employees_pay_periods = $this->sys->db->query("SELECT `pay_period_id` FROM `employee_punch` WHERE `employee_id`=:employee_id", array(
            ':employee_id' => (int) $employee_id
        ));
        
        if (empty($employees_pay_periods)) {
            $employees_pay_periods = array('pay_periods' => array());    
        } else {
            foreach ($employees_pay_periods as $pay_period) {
                $employees_pay_periods['pay_periods'][] = $pay_period['pay_period_id'];
            }
        }

        $table_periods_index = array(0, 1, 2, 3);
        $tables = array(array(), array(), array(), array());
        $return_tables = array();
        $table_index = -1;
        $rows = 0;
        
        for ($i=0; $i<count($pay_periods); $i++) {
            $table_index = ($table_index < 3) ? $table_index+1: 0;
            
            $tables[$table_index][] = $pay_periods[$table_periods_index[$table_index]];
            $temp_rows = count($tables[$table_index]);
            
            if ($temp_rows > $rows) {
                $rows = $temp_rows;
            }
            
            $table_periods_index[$table_index] += 4;
        }
        
        foreach ($tables as $key=>$table) {
            $trs = 0;
            $return_tables[$key] = '<tbody>';
            
            foreach ($table as $dates) {
                $trs++;
                if (in_array($dates['pay_period_id'], $employees_pay_periods['pay_periods'])) {
                    $bold = 'bold';
                } else {
                    $bold = "";
                }
                
                if ($selected_pay_period == $dates['pay_period_id']) {
                    $italic = ' italic';
                } else {
                    $italic = '';
                }
                
                $return_tables[$key] .= '<tr"><td class="' . $bold . $italic . '" onClick="loadPayPeriod(' . (int) $employee_id . ', ' . $dates['pay_period_monday'] . ')">' . date($this->_dateFormat, $dates['pay_period_monday']) . '</td><td class="' . $bold . $italic . '" onClick="loadPayPeriod(' . (int) $employee_id . ', ' . $dates['pay_period_monday'] . ')">' . date($this->_dateFormat, $dates['pay_period_sunday']) . '</td></tr>';
            }
            
            for ($i=$trs; $i<$rows; $i++) {
                $return_tables[$key] .= '<tr><td><br /></td><td><br /></td></tr>';
            }
            
            $return_tables[$key] .= '</tbody>';
        }
        
        return $return_tables;
    } //End generate_previous_pay_period_table
} //End payPeriod_views

//End file
