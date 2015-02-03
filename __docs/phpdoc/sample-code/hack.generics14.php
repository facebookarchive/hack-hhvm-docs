<?hh

abstract class Identity<T> {
  private T $id;
  public function __construct(T $id) {
    $this->id = $id;
  }
  public function getID(): T {
    return $this->id;
  }
}

interface IFoo {}

class CA {}
class CB extends CA {}

class Bar implements IFoo {}
class Baz implements IFoo {}
class Biz {}

final class AnyIdentity<T> extends Identity<T> {}
final class CAIdentity<T as CA> extends Identity<T> {}
final class CBIdentity<T as CB> extends Identity<T> {}
final class FooIdentity<T as IFoo> extends Identity<T> {}

function main_constraints(): void {
  $ai = new AnyIdentity("Hello");
  $ai2 = new AnyIdentity(new Biz());

  $cb = new CBIdentity(new CB());
  $cb2 = new CBIdentity(new CA());  // HACK ERROR!

  $ca = new CAIdentity(new CA());
  $ca2 = new CAIdentity(new CB());

  $fi = new FooIdentity(new Bar());
  $fi2 = new FooIdentity(new Baz());
  $fi3 = new FooIdentity(new Biz()); // HACK ERROR!
}

main_constraints();
