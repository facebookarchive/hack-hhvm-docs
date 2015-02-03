// file1.php
<?hh

class CParent {
  public function doStuff(): void {
    return $this->implementation();
  }
  protected function implementation(): void {
    echo 'parent implementation', "\n";
  }
}

// file2.php
<?hh
class Child extends CParent {
  <<__Override>>
  protected function implementation(): void {
    echo 'child implementation', "\n";
  }
}
