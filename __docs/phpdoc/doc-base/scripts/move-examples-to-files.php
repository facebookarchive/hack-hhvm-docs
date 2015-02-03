<?hh

define('REGEX_ID_FIND', '#xml:id="([^\s]+)"#');
// Freaking monstrosity!
define('REGEX_PROGRAM_LISTING','#<programlisting(?: role="php")?( location="[a-zA-Z0-9/]+")?>\s*<!\[CDATA\[([\S\s]*)\]\]>[\S\s]*</programlisting>#isU');
define('SAMPLE_CODE_ROOT', __DIR__."/../../sample-code");

function get_next_filename_suffix(string $root, string $file_prefix): int {
  $suffix = 1;
  $fp_len = strlen($file_prefix);
  $di = new RecursiveDirectoryIterator($root,
                                       RecursiveDirectoryIterator::SKIP_DOTS);
  $iter = new RecursiveIteratorIterator($di);
  foreach ($iter as $file) {
    $name = pathinfo($file, PATHINFO_FILENAME);
    // if name is hack.array.empty1, this should return 2
    if (strpos($name, $file_prefix) !== false) {
      // strpos should most likely be 0 usually, then add the length of
      // the prefix to get the actual numerical suffix
      $file_suffix = substr($name, strpos($name, $file_prefix) + $fp_len);
      if (intval($file_suffix) >= $suffix) {
        $suffix = intval($file_suffix) + 1;
      }
    }
  }
  return $suffix;
}

function replace_inline_examples_with_files(string $xml_file): void {
  if (!is_file($xml_file)) {
    return;
  }
  $xml_content = file_get_contents($xml_file);
  $matches = array();

  // Find the main xml id of the file to assist in naming example files.
  preg_match(REGEX_ID_FIND, $xml_content, $matches);
  $filename_prefix = "";
  if (!empty($matches[1])) {
    $filename_prefix = $matches[1];
  } else {
    $filename_prefix = basename($xml_file);
  }

  $suffix = get_next_filename_suffix(SAMPLE_CODE_ROOT, $filename_prefix);

  $matches = array();
  $count = preg_match_all(REGEX_PROGRAM_LISTING, $xml_content, $matches);
  for ($i = 0; $i < $count; $i++) {
    if (!empty($matches[1][$i])) { // ( location="[a-zA-Z0-9/]")?
      continue; // We already have file location, assume it has been processed
    }
    // The (([\S\s]*)) in [CDATA\[([\S\s]*)\]\], i.e. the program
    if (!empty($matches[2][$i])) {
      $filename = SAMPLE_CODE_ROOT . "/" . $filename_prefix
                . strval($suffix) . ".php";
      file_put_contents($filename, ltrim($matches[2][$i]));
      $search = $matches[0][$i]; // The entire <programlisting>...</programlisting>
      $replace = '<programlisting role="php" location="' . $filename_prefix
               . strval($suffix) . '.php" />';
      $xml_content = str_replace($search, $replace, $xml_content);
      $suffix++;
    }
  }
  file_put_contents($xml_file, $xml_content);
  return;
}

function get_xml_files(string $root): Set<string> {
  $xml_files = new Set();
  $di = new RecursiveDirectoryIterator($root,
                                       RecursiveDirectoryIterator::SKIP_DOTS);
  $iter = new RecursiveIteratorIterator($di);
  foreach ($iter as $file) {
    $filename = $file->getPathname();
    if ($file->isFile() && pathinfo($file, PATHINFO_EXTENSION) === 'xml') {
      $xml_files->add($filename);
    }
  }
  return $xml_files;
}

// Staring only with the Hack examples initially.
$root = __DIR__ . "/../../en/hack";
$files = get_xml_files($root);
foreach ($files as $file) {
  replace_inline_examples_with_files($file);
}
$root = __DIR__ . "/../../en/hackref";
$files = get_xml_files($root);
foreach ($files as $file) {
  replace_inline_examples_with_files($file);
}
