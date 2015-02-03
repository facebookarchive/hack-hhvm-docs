<?hh

class PettingZoo {
  private BunnyRabbit $fluffy;

  public function pet(): void {
    $this->fluffy = get_bunny();
    pet_bunny($this->fluffy);
  }
}
