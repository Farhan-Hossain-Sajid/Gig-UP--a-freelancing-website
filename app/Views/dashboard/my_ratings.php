<?php require __DIR__ . '/../partials/header.php'; ?>
<h2>My Average Rating</h2>
<p>Avg: <?=htmlspecialchars($ratings['avg'])?> (<?=htmlspecialchars($ratings['count'])?>)</p>
<?php require __DIR__ . '/../partials/footer.php'; ?>