<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Gig Up! – Login to Gig UP!</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <h2>Login to Gig UP!</h2>
    <form action="php/login.php" method="post">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <select name="role" required>
        <option value="">Select Role</option>
        <option value="buyer">Buyer</option>
        <option value="seller">Seller</option>
      </select>
      <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.html">Register here</a></p>
    <!-- at bottom‐right corner of your login form page -->
    <div style="position:absolute; bottom:1em; right:1em;">
    <a href="admin_login.php">Admin Login</a>
    </div>
  </div>
</body>
</html>
