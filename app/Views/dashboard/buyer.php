<?php require __DIR__ . '/../partials/header.php'; ?>
<h2>Welcome, <?=htmlspecialchars($_SESSION['user']['name'])?></h2>
<nav><!-- links --></nav>
<?php require __DIR__ . '/../partials/footer.php'; ?>