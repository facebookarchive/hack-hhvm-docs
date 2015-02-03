<?hh
class Obj<Tk, Tv> implements ArrayAccess<Tk, Tv> {
  private Map<Tk, Tv> $container;
  public function __construct() {
    $this->container = Map {};
  }
  public function offsetSet(Tk $key, Tv $value): this {
    if (!is_null($key)) {
      $this->container[$key] = $value;
    }
    return $this;
  }
  public function offsetExists(Tk $offset): bool {
    return isset($this->container[$offset]);
  }
  public function offsetUnset(Tk $offset): this {
    unset($this->container[$offset]);
    return $this;
  }
  public function offsetGet(Tk $offset): ?Tv {
    return isset($this->container[$offset]) ? $this->container[$offset] : $
  }
}

function main(): void {
  $o = new Obj();
  $o->offsetSet(3, 3);
  var_dump($o);
}

main();
