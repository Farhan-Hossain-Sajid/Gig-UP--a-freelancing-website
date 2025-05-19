<?php require __DIR__ . '/../partials/header.php'; ?>
<h2>Chat for Order <?=htmlspecialchars($orderId)?></h2>
<div id="chatbox"><!-- loop $convo --></div>
<form method="post" action="/chat/send">
  <!-- message -->
</form>
<?php require __DIR__ . '/../partials/footer.php'; ?>