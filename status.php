<?hh

$check = isset($_GET['check'])        ? $_GET['check']        : 'live';
$host  = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'www.hhvm.com';

switch($check) {
  case 'live':
    echo '1-AM-ALIVE';
    exit;
  case 'date':
    list(,$date) = explode('+', HHVM_VERSION.'+N/A');
    echo $date;
    exit;
  case 'version':
    echo HHVM_VERSION;
    exit;
  case 'stats':
    $stats = [];
    if ($host == 'nightly.hhvm.com') {
      $stats = get_stats('/home/hiphop/nightly/nightly.json');
    }
    $stats['version'] = HHVM_VERSION;
    header('Content-type: application/json');
    echo json_encode($stats, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    exit;
}




function get_stats(string $file): array {
  if (!is_readable($file)) return [];
  $contents = file_get_contents($file);
  $decoded = @json_decode($contents, true);
  if (!is_array($decoded)) return [];
  return $decoded;
}
