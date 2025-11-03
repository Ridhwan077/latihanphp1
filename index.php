<?php 
session_start();

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'includes/head.php'; ?>
  <link rel="stylesheet" href="assets/login.css">
  <title>Login</title>
</head>
<body>

  <main>
    <div class="login-container">
      <h2>Login</h2>
      <form action="login_action.php" method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="login">Login</button>
      </form>
    </div>
  </main>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
