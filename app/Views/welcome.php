<!DOCTYPE html>
<html>
<head><title><?= htmlspecialchars($title)?></title></head>
<body>
  <h1><?= htmlspecialchars($data['message'] ?? 'No message') ?></h1>
  <p>time: <?= htmlspecialchars($data['time'] ?? '') ?></p>
</body>
</html>
