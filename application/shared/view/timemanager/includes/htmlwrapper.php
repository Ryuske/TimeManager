<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <?php
        if (is_array($this->sys->template->meta)) {
            echo '<meta http-equiv="refresh" content="' . $this->sys->template->meta[0] . ';URL=' . $this->sys->template->meta[1] . '" />';
        }
        ?>

        <link type="text/css" rel="stylesheet" href="{timemanager_assets}css/bootstrap.css" />
        <link type="text/css" rel="stylesheet" href="{timemanager_assets}css/bootstrap-theme.css" />
        <link type="text/css" rel="stylesheet" href="{timemanager_assets}css/redmond/jquery.css" />
        <link type="text/css" rel="stylesheet" href="{timemanager_assets}css/main.css" />


        <script src="{timemanager_assets}js/includes/jquery.js"></script>
        <script src="{timemanager_assets}js/includes/bootstrap.js"></script>
        <script src="{timemanager_assets}js/includes/jquery-ui.js"></script>
        <script src="{timemanager_assets}js/main.js"></script>
        <script src="{timemanager_assets}js/dialogs.js"></script>
        <script src="{timemanager_assets}js/functions.js"></script>


        <title>{title}</title>
</head>
<body>
    <div class="container">
        {navbar}
        {page}
    </div>
    </body>
</html>
