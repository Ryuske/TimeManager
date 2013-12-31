<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="{timemanager_root}main" class="navbar-brand">Time Manager</a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li {home_active}><a href="{timemanager_root}main">Home</a></li>
        <li {jobs_active}><a href="{timemanager_root}jobs">Jobs - Quotes</a></li>
        <li {about_active}><a href="{timemanager_root}about">About</a></li>
        <li {settings_active}><a href="{timemanager_root}settings">Settings</a></li>
        <li><a href="{timemanager_root}logout">Logout</a></l>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</div>
