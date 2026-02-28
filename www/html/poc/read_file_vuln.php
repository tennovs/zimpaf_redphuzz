<?php
// read_file.php
// Safe example: allowlist keys mapped to files in ./data/
// -----------------------------------------------------
// WARNING: Do NOT accept arbitrary file paths from users.
// The commented-out line below shows the unsafe pattern:
//    // $contents = file_get_contents($_GET['path']);
// Do not uncomment that in any real environment.
// -----------------------------------------------------

// allowlist: keys -> actual file paths
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

$allowed = [
    'about' => __DIR__ . '/data/about.txt',
    'terms' => __DIR__ . '/data/terms.txt',
    'policy' => __DIR__ . '/data/policy.txt',
];

$requested = $_GET['file'] ?? '';

$message = '';
$content = '';

if ($requested !== '') {
  // UNSAFE: do not use in production
  $unsafe_path = $allowed[$requested];
  // $unsafe_path = $_GET['file'];
  $content = file_get_contents($unsafe_path);
  // echo htmlspecialchars($content);
    // if (!array_key_exists($requested, $allowed)) {
    //     $message = 'Invalid file requested.';
    // } else {
    //     $path = $allowed[$requested];
    //
    //     // Extra safety: ensure the realpath is inside our data directory
    //     $realPath = realpath($path);
    //     $dataDir = realpath(__DIR__ . '/data');
    //
    //     if ($realPath === false || strpos($realPath, $dataDir) !== 0) {
    //         $message = 'File not found or access denied.';
    //     } else {
    //         // Safe read
    //         $contents = @file_get_contents($realPath);
    //         if ($contents === false) {
    //             $message = 'Failed to read file.';
    //         } else {
    //             // Show raw text; escape for HTML output
    //             $content = htmlspecialchars($contents);
    //         }
    //     }
    // }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Safe File Reader (allowlist)</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 2rem; }
    label, input, button { font-size: 1rem; }
    pre { background:#f4f4f4; padding:1rem; border-radius:4px; }
    .note { color: #6a6a6a; font-size: .9rem; }
  </style>
</head>
<body>
  <h1>Read a file (safe allowlist)</h1>

  <p class="note">
    Choose a file key and click "Read". This demo only serves known files from the <code>data/</code> folder.
  </p>

  <form method="GET" action="">
    <label for="file">File:</label>
    <select id="file" name="file" required>
      <option value="">-- select --</option>
      <?php foreach ($allowed as $k => $_p): ?>
        <option value="<?php echo htmlspecialchars($k); ?>"
          <?php if ($requested === $k) echo 'selected'; ?>>
          <?php echo htmlspecialchars($k); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button type="submit">Read</button>
  </form>

  <hr>

  <?php if ($message): ?>
    <p style="color: darkred;"><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>

  <?php if ($content !== ''): ?>
    <h2>Contents of <?php echo htmlspecialchars($requested); ?>:</h2>
    <pre><?php echo $content; ?></pre>
  <?php endif; ?>

  <hr>
<!--  <h3>Developer note (do not run):</h3> -->
<!-- <p class="note">
   The following is an <strong>unsafe</strong> pattern shown for learning only (do not copy into any server):
 </p>
-->
  <pre>

 </pre>
</body>
</html>
