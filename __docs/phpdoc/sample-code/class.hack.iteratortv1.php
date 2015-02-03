<?hh
class myIterator<Tv> implements Iterator<Tv> {
  private $position = 0;
  private $array = array(
    "firstelement",
    "secondelement",
    "lastelement",
  );

  public function __construct() {
    $this->position = 0;
  }

  function rewind(): void {
    var_dump(__METHOD__);
    $this->position = 0;
  }

  function current(): Tv {
    var_dump(__METHOD__);
    return $this->array[$this->position];
  }

  function next(): void {
    var_dump(__METHOD__);
    ++$this->position;
  }

  function valid(): bool {
    var_dump(__METHOD__);
    return isset($this->array[$this->position]);
  }
}

$it = new myIterator;

foreach($it as $value) {
  var_dump($value);
  echo "\n";
}
