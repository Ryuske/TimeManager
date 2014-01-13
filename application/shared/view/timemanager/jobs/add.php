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
                <input class="form-control" name="job_quantity" type="text" placeholder="Quantity" required="required" />
                <input class="form-control inline_date" name="job_start_date" type="text" placeholder="Start Date" required="required" />
                <input class="form-control inline_date" name="job_due_date" type="text" placeholder="Due Date" required="required" />
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