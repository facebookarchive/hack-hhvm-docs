<?hh
class DuplicateStrIterator implements KeyedIterator<string, string> {
  protected $it = 0;

  public function current() {
    return $this->it;
  }

  public function key() {
    return chr(65 + ($this->it % 5));
  }

  public function next() {
    $this->it++;
  }

  public function rewind() {
    $this->it = 0;
  }

  public function valid() {
    return $this->it < 10;
  }
}

$it = new DuplicateStrIterator;

foreach($it as $key => $value) {
  var_dump($key, $value);
  echo "\n";
}
