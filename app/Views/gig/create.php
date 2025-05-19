<!-- app/Views/gig/create.php -->
<?php require __DIR__.'/../partials/header.php'; ?>

<h2>Create a New Gig</h2>
<form method="post" action="/gigs">
  <label>Title</label>
  <input name="title" required>

  <label>Description</label>
  <textarea name="description" required></textarea>

  <label>Category</label>
  <input name="category" required>

  <label>Price</label>
  <input type="number" name="price" step="0.01" required>

  <label>Delivery Days</label>
  <input type="number" name="delivery_days" required>

  <button type="submit">Publish</button>
</form>

<?php require __DIR__.'/../partials/footer.php'; ?>
