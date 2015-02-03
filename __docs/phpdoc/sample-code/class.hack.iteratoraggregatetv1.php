<?hh
class myData<string> implements IteratorAggregate<string> {
  public string $property1 = "Public property one";
  public string $property2 = "Public property two";
  public string $property3 = "Public property three";

  // Using constructor promotion
  public function __construct(public string $property) {
  }

  public function getIterator():  {
    return new ArrayIterator($this);
  }
}

$obj = new myData("last property");

foreach($obj as $value) {
  var_dump($value);
  echo "\n";
}
