<?hh

interface IFun {
  public function haveFun(): void;
}

class FunHaver implements IFun {
  public function haveFun(): void {}
  public function haveLotsOfFun(): void {}
}

function get_fun(): IFun {
  return new FunHaver();
}

function do_fun(): void {
  $f = get_fun();

  // $f is an IFun, so we can go ahead and do this:
  $f->haveFun();

  // But we need to use invariant if we want to use it as a FunHaver without a
  // type error.
  invariant($f instanceof FunHaver, 'Expected a FunHaver');
  $f->haveLotsOfFun();
}
