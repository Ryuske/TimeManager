<div class="jumbotron">
    <h1>TimeClock</h1>
    <p>Edit job</p>
</div>
<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        <div class="well">
        <form class="form" method="post" action="">
            <input type="hidden" name="id" value="{job['job_id']}" />
            <div class="group">
                <label>
                    Client &raquo;
                    <span class="ui-icon ui-icon-plus form_label_icon" onclick="client_operations('add')"></span>
                    <span class="ui-icon ui-icon-pencil form_label_icon" onclick="client_operations('edit')"></span>
                    <span class="ui-icon ui-icon-trash form_label_icon" onclick="client_operations('remove')"></span>
                </label>
                <select class="form-control" name="client">
                    <?php
                    for ($i=0; $i<count($this->sys->template->clients); $i++) {
                        $selected = ($this->sys->template->job['client'] == $this->sys->template->clients[$i]['client_id']) ? 'selected="selected"' : '';
                        ?>
                        <option value="{clients[<?php echo $i; ?>]['client_id']}" <?php echo $selected; ?>>{clients[<?php echo $i; ?>]['client_name']}</option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="group">
                <input class="form-control" name="uid" type="text" placeholder="Job UID" value="{job['job_uid']}" />
                <label class="checkbox">
                    <input type="checkbox" name="generate_uid" value="generate-uid" />
                    Generate ID
                </label>
            </div>
            <div class="group">
                <input class="form-control" name="job_name" type="text" value="{job['job_name']}" required="required" />
            </div>
            <div class="group">
                <label>Status</label>
                <select class="form-control" name="status">
                    <?php
                    $status = array('', '', '');
                    switch ($this->sys->template->job['status']) {
                        case 'na':
                            $status[0] = 'selected="selected"';
                            break;
                        case 'wip':
                            $status[1] = 'selected="selected"';
                            break;
                        case 'c':
                            $status[2] = 'selected="selected"';
                            break;
                        default:
                            //Do nothing
                    }
                    ?>
                    <option value="na" <?php echo $status[0]; ?>>Not Started</option>
                    <option value="wip" <?php echo $status[1]; ?>>In Progress</option>
                    <option value="c" <?php echo $status[2]; ?>>Completed</option>
                </select>
            </div>
            <div class="group">
                <label>Quote</label>
                <table class="table">
                    <?php
                    $time = array('', '', '');
                    for ($i = 0; $i < count($this->sys->template->categories); $i=$i+3) {
                        $time[0] = (array_key_exists($this->sys->template->categories[$i]['category_id'], $this->sys->template->job['quoted_time'])) ? $this->sys->template->job['quoted_time'][$this->sys->template->categories[$i]['category_id']] : '0';

                        echo '<tr>';
                        echo '
                            <td>
                                <label>' . $this->sys->template->categories[$i]['category_name'] . '</label>
                                <input class="form-control" name="quote[' . $this->sys->template->categories[$i]['category_id'] . ']" type="text" value="' . $time[0] . '" />
                            </td>
                        ';
                        if (array_key_exists(($i+1), $this->sys->template->categories)) {
                            $time[1] = (array_key_exists($this->sys->template->categories[$i+1]['category_id'], $this->sys->template->job['quoted_time'])) ? $this->sys->template->job['quoted_time'][$this->sys->template->categories[$i+1]['category_id']] : '0';
                            
                            echo '
                                <td>
                                    <label>' . $this->sys->template->categories[$i+1]['category_name'] . '</label>
                                    <input class="form-control" name="quote[' . $this->sys->template->categories[$i+1]['category_id'] . ']" type="text" value="' . $time[1] . '" />
                                </td>
                            ';
                        } else {
                            echo '<td></td>';
                        }
                        if (array_key_exists(($i+2), $this->sys->template->categories)) {
                            $time[2] = (array_key_exists($this->sys->template->categories[$i+2]['category_id'], $this->sys->template->job['quoted_time'])) ? $this->sys->template->job['quoted_time'][$this->sys->template->categories[$i+2]['category_id']] : '0';
                            
                            echo '
                                <td>
                                    <label>' . $this->sys->template->categories[$i+2]['category_name'] . '</label>
                                    <input class="form-control" name="quote[' . $this->sys->template->categories[$i+2]['category_id'] . ']" type="text" value="' . $time[2] . '" />
                                </td>
                            ';
                        } else {
                            echo '<td></td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
            {response}
            <input class="form-control" name="edit_job" type="submit" value="Edit Job" />
            </form>
        </div>
    </div>
</div>
<div class="client_add_dialog">
    <div class="dialog_text bold">
        Client to Add
    </div>
    <form class="add_client_form" method="post" action="">
        <div class="dialog_input">
            <input type="text" name="client_name" placeholder="Clients' Name" />
        </div>
        <input type="hidden" name="add_client" value="add_client" />
    </form>
</div> <!-- END client_add_dialog -->

<div class="client_edit_dialog">
    <div class="dialog_text">
        Editing: <span class="bold client_name"></span>
    </div>
    <form class="edit_client_form" method="post" action="">
        <div class="dialog_input">
            <input type="text" name="client_name" value="" />
        </div>
        <input type="hidden" name="client_id" value="" />
        <input type="hidden" name="edit_client" value="edit_client" />
    </form>
</div> <!-- END client_edit_dialog -->

<div class="client_remove_dialog">
    <div class="dialog_text">
        Are you sure you want to remove client <span class="bold client_name"></span>?
    </div>
    <form class="remove_client_form" method="post" action="">
        <input type="hidden" name="client_id" value="" />
        <input type="hidden" name="remove_client" value="remove_client" />
    </form>
</div> <!-- END client_remove_dialog -->