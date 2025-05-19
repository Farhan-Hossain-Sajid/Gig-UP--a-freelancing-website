<?php require __DIR__ . '/../partials/header.php'; ?>

<h2>Browse Gigs</h2>

<form method="get">
  <input name="title" placeholder="Search title…" value="<?=htmlspecialchars($_GET['title']??'') ?>">
  <select name="category">
    <option value="">All categories</option>
    <!-- add more categories -->
  </select>
  <input name="min_price" placeholder="Min price…" value="<?=htmlspecialchars($_GET['min_price']??'') ?>">
  <input name="max_price" placeholder="Max price…" value="<?=htmlspecialchars($_GET['max_price']??'') ?>">
  <input name="max_delivery" placeholder="Max days…" value="<?=htmlspecialchars($_GET['max_delivery']??'') ?>">
  <button type="submit">Filter</button>
</form>

<?php foreach ($gigs as $g): ?>
  <div class="gig-card">
    <h3><a href="/gigs/<?= $g['id'] ?>"><?= htmlspecialchars($g['title']) ?></a></h3>
    <p>By <?= htmlspecialchars($g['seller_name']) ?></p>
    <p>Price: TK<?= number_format($g['price'],2) ?></p>
    <p>Delivery: <?= $g['delivery_days'] ?> days</p>
  </div>
<?php endforeach; ?>

<?php require __DIR__ . '/../partials/footer.php'; ?>