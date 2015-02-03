<?hh

class Base {
  private int $x = 0;
  public function setX(int $new_x): this {
    $this->x = $new_x;
    // $this has type "this"
    return $this;
  }
  public static function newInstance(): this {
    // new static() has type "this"
    return new static();
  }
  public function newCopy(): this {
    // This would not type check with self::, but static:: is ok
    return static::newInstance();
  }
  // You can also say Awaitable<this>;
  public async function genThis(): Awaitable<this> {
    return $this;
  }
}

final class Child {
  public function newChild(): this {
    // This is OK because Child is final.
    // However, if Grandchild extends Child, then this would be wrong, since
    // $grandchild->newChild() should returns a Child instead of a Grandchild
    return new Child();
  }
}
