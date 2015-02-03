<?hh

interface IFaz {}
interface IFar extends IFaz {}
class Faz implements IFaz {}
class Far implements IFar {}
class NoIFaz {}

class Bip {
  public function get<T as IFaz>(T $x): T {
    return $x;
  }
}

function main_constraints_gm(): void {
  $bip = new Bip();
  $bip->get(new Faz());
  $bip->get(new Far());
  $bip->get(new NoIFaz()); // Hack error;
}

main_constraints_gm();
