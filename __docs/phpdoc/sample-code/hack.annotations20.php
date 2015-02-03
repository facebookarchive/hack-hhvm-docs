<?hh

class Base {
  public static function newBase(): this {
    // ERROR! The "this" return type means that $child->newBase()
    // should return a Child, but it always returns a Base!
    return new Base();
  }

  public static function newBase2(): this {
    // ERROR! This is wrong for the same reason that new Base() is wrong
    return new self();
  }

  // This function is fine
  abstract public static function goodNewInstance(): this;

  public static function badNewInstance(): this {
    // ERROR! Child::badNewInstance() would call Base::goodNewInstance() which is wrong
    return self::goodNewInstance();
  }
}
