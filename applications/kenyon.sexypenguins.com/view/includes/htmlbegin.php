<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <?php
        if (is_array($this->system_di->template->meta)) {
            echo '<meta http-equiv="refresh" content="' . $this->system_di->template->meta[0] . ';URL=' . $this->system_di->template->meta[1] . '" />';
        }
        ?>

        <link type="text/css" rel="stylesheet" href="{timeclock_root}webContent/css/bootstrap.css" />
        <link type="text/css" rel="stylesheet" href="{timeclock_root}webContent/css/bootstrap-theme.css" />
        <link type="text/css" rel="stylesheet" href="{timeclock_root}webContent/css/redmond/jquery.css" />
        <link type="text/css" rel="stylesheet" href="{timeclock_root}webContent/css/main.css" />


        <script src="{timeclock_root}webContent/js/jquery.js"></script>
        <script src="{timeclock_root}webContent/js/bootstrap.js"></script>
        <script src="{timeclock_root}webContent/js/jquery-ui.js"></script>
        <script src="{timeclock_root}webContent/js/main.js"></script>


        <title>{title}</title>
</head>
<body>
    <div class="container">

