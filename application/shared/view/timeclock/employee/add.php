<div class="jumbotron">
    <h1>TimeClock</h1>
    <p>Add a new employee</p>
</div>
<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        <div class="well">
        <form class="form" method="post" action="">
                <div class="group">
                    <input class="form-control" name="firstname" type="text" placeholder="Employees' First Name" required="required" />
                    <input class="form-control" name="lastname" type="text" placeholder="Employees' Last Name" required="required" />
                </div>
                <div class="group">
                    <label>
                        Category &raquo;
                        <span class="ui-icon ui-icon-plus form_label_icon" onclick="category_operations('add')"></span>
                        <span class="ui-icon ui-icon-pencil form_label_icon" onclick="category_operations('edit')"></span>
                        <span class="ui-icon ui-icon-trash form_label_icon" onclick="category_operations('remove')"></span>
                    </label>
                    <select class="form-control" name="category">
                        <?php
                        for ($i=0; $i<count($this->sys->template->categories); $i++) {
                            ?>
                            <option value="{categories[<?php echo $i; ?>]['category_id']}">{categories[<?php echo $i; ?>]['category_name']}</option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <hr />
                <input class="form-control" name="uid" type="text" placeholder="Employees' Badge UID" />
                <label class="checkbox">
                    <input type="checkbox" name="generate_uid" value="generate-uid" checked="checked" />
                    Generate 4-byte hex UID
                </label>
                <hr />
                <input class="form-control" name="username" type="text" placeholder="Optional Username" />
                <input class="form-control" name="password" type="password" placeholder="Password (Required With Username)" />
                {response}
                <input class="form-control" name="add_employee" type="submit" value="Add Employee" />
            </form>
        </div>
    </div>
</div>
<div class="category_add_dialog">
    <div class="dialog_text bold">
        Category to Add
    </div>
    <form class="add_category_form" method="post" action="">
        <div class="dialog_input">
            <input type="text" name="category_name" placeholder="Categories' Name" />
        </div>
        <input type="hidden" name="add_category" value="add_category" />
    </form>
</div> <!-- END category_add_dialog -->

<div class="category_edit_dialog">
    <div class="dialog_text">
        Editing: <span class="bold category_name"></span>
    </div>
    <form class="edit_category_form" method="post" action="">
        <div class="dialog_input">
            <input type="text" name="category_name" value="" />
        </div>
        <input type="hidden" name="category_id" value="" />
        <input type="hidden" name="edit_category" value="edit_category" />
    </form>
</div> <!-- END category_edit_dialog -->

<div class="category_remove_dialog">
    <div class="dialog_text">
        Are you sure you want to remove category <span class="bold category_name"></span>?
    </div>
    <form class="remove_category_form" method="post" action="">
        <input type="hidden" name="category_id" value="" />
        <input type="hidden" name="remove_category" value="remove_category" />
    </form>
</div> <!-- END category_remove_dialog -->