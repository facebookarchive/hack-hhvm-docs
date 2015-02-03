<?hh

function create_updateA(string $key, string $comments) {
  $comments = ($comments === '')
    ? 'Updated without comments.'
    : 'Updated with the following comments: ' . $comments;

  $vc = VC();
  $ua = get_update_array($key, $comments);

  regsiter(array('A', 'B'), $vc, $ua, 'update');
}
