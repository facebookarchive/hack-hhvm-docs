<?hh

class PettingZoo {
  private FluffyBunny $fluffy;

  public function __construct(FluffyBunny $bunny) {
    $this->doOtherInit();
    $this->fluffy = new FluffyBunny();
  }

  protected function doOtherInit(): void { }
}
