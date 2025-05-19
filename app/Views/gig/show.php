<?php require __DIR__ . '/../partials/header.php'; ?>
<h2><?= htmlspecialchars($gig['title']) ?></h2>
<p><?= nl2br(htmlspecialchars($gig['description'])) ?></p>
<p>Price: TK<?= number_format($gig['price'],2) ?></p>
<?php require __DIR__ . '/../partials/footer.php'; ?>