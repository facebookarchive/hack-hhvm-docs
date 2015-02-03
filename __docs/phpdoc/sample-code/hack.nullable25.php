<?hh

class PettingZoo {
  private BunnyRabbit $fluffy;

  public function __construct() {
    $this->fluffy = get_bunny();
  }

  public function pet(): void {
    pet_bunny($this->fluffy);
  }
}
