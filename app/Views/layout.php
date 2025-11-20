<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Library Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>

<body>
  <?= $this->include('partials/navbar') ?>
  <div id="toast-container" class="toast-container"></div>
  <main>
    <?= $this->renderSection('content') ?>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url('assets/js/toast.js') ?>"></script>
</body>

</html>
