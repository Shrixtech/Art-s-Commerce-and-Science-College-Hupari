<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/panel.php';
requireLogin('admin');
panelHeader('Logs Viewer','admin');
$lines = is_file(LOG_FILE) ? file(LOG_FILE) : [];
$tail = array_slice($lines, -300);
?>
<pre class="bg-dark text-light p-3 rounded" style="max-height:70vh;overflow:auto"><?= e(implode('', $tail)) ?></pre>
<?php panelFooter(); ?>
