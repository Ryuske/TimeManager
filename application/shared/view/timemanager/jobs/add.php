<div class="jumbotron">
    <h1>Time Manager</h1>
    <p>Add a new job</p>
</div>
<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        <div class="well">
        <form class="form" method="post" action="">
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
                        ?>
                        <option value="{clients[<?php echo $i; ?>]['client_id']}">{clients[<?php echo $i; ?>]['client_name']}</option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="group">
                <input class="form-control" name="uid" type="text" placeholder="Job UID" />
                <label class="checkbox">
                    <input type="checkbox" name="generate_uid" value="generate-uid" checked="checked" />
                    Generate ID
                </label>
            </div>
            <div class="group">
                <input class="form-control" name="job_name" type="text" placeholder="Job Name" required="required" />
            </div>
            <div class="group">
                <label>Quote</label>
                <table class="table">
                    <?php
                    $substr_end = 12;
                    for ($i = 0; $i < count($this->sys->template->categories); $i=$i+3) {
                        echo '<tr>';
                        echo '
                            <td>
                                <label>' . substr(ucwords(strtolower($this->sys->template->categories[$i]['category_name'])), 0, $substr_end) . '</label>
                                <input class="form-control" name="quote[' . $this->sys->template->categories[$i]['category_id'] . ']" type="text" placeholder="Time" />
                            </td>
                        ';
                        if (array_key_exists(($i+1), $this->sys->template->categories)) {
                            echo '
                                <td>
                                    <label>' . substr(ucwords(strtolower($this->sys->template->categories[$i+1]['category_name'])), 0, $substr_end) . '</label>
                                    <input class="form-control" name="quote[' . $this->sys->template->categories[$i+1]['category_id'] . ']" type="text" placeholder="Time" />
                                </td>
                            ';
                        } else {
                            echo '<td></td>';
                        }
                        if (array_key_exists(($i+2), $this->sys->template->categories)) {
                            echo '
                                <td>
                                    <label>' . substr(ucwords(strtolower($this->sys->template->categories[$i+2]['category_name'])), 0, $substr_end) . '</label>
                                    <input class="form-control" name="quote[' . $this->sys->template->categories[$i+2]['category_id'] . ']" type="text" placeholder="Time" />
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
            <input class="form-control" name="add_job" type="submit" value="Add Job" />
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