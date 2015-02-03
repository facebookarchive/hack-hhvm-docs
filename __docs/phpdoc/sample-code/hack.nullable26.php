<?hh

class PettingZoo {
  private ?BunnyRabbit $fluffy;

  public function getFluffy(): BunnyRabbit {
    // A local variable is being used due to the
    // nullable member variables issue
    $fluffy = $this->fluffy;
    if (!$fluffy) {
      $fluffy = $this->fluffy = get_bunny();
    }
    return $fluffy;
  }

  public function pet(): void {
    pet_bunny($this->getFluffy());
  }
}
