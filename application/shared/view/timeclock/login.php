<form class="form-signin" method="post" action="{timeclock_root}index">
    <h2 class="form-signin-heading">Welcome | Sign In</h2>
    <input class="form-control" name="username" type="text" placeholder="Username" />
    <input class="form-control" name="password" type="password" placeholder="Password" />
    {login_failed}
    <input class="form-control" name="login" type="submit" value="Login" />
</form>
