<?hh

function setup_saf() {
  $rd = shape('id' => null, 'url' => null, 'count' => 0);
  $rd['id'] = '573065673A34Y';
  $rd['url'] = 'http://facebook.com';
  var_dump($rd);
  var_dump(foo_saf($rd));


  // This should cause a Hack error
  // But this will still run in HHVM
  $rd['count'] = 'I should error';
  var_dump(foo_saf($rd));
}
