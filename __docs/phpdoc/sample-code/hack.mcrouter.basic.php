<?hh

$router = MCRouter::createSimple(array(
  '127.0.0.1:11211',
  '[::1]:11211',
));

$waitHandles = Vector {
  $router->set("FOO", "bar"),
  $router->get("BAR"),
  $router->del("BAZ"),
  $router->incr("QUX", 1234),
};

$results = HH\asio\v($waitHandles)->join();

// Results 0 and 2 are uninteresting,
// failures result in exceptions
var_dump($results[1], $results[3]);

