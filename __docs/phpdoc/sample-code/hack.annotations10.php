<?hh
class TupleTest {
  // This is a Vector of tuples. Notice how the "tuple" reserved
  // word is not used when annotating.
  private Vector<(string, string)> $test = Vector {};
  // The return type is a tuple. Again, the "tuple" reserved
  // word is not used.
  public function bar(): (string, string) {
    return $this->test[0];
  }
  public function foo() {
    // But to use an actual tuple, use the "tuple" reserved word
    $this->test->add(tuple('hello', 'world'));
  }
}
