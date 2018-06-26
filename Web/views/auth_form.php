<!DOCTYPE html>
<html>
<head>
<title>Login fb</title>

<link href="Web/assets/css/auth_form_css.css" rel="stylesheet" type="text/css"  />
</head>

<body>

<section class="login-form-wrap">
  <h1>Sup'Internet connect</h1>
  <form class="login-form" method="POST" action="?action=confirmForm">
    <label>
      <input type="email" name="email" class="" required placeholder="Email">
    </label>
    <label>
      <input type="password" name="password" required placeholder="Password">
    </label>
      <input type="hidden" name="auth_token" value="<?php echo $_GET['auth_token']; ?>" >
    <input type="submit" value="Login">
  </form>
  <h5><a href="#">Forgot password</a></h5>
</section>
</body>

</html>
