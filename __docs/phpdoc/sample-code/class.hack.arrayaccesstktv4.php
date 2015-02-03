<?hh
// Assume Obj implements ArrayAccess<Tk, Tv>

function main(): void {
  $o = new Obj();
  $o->offsetSet(3, 3); // works fine
  $o[4] = 4; // NOT SUPPORTED BY HACK
}
