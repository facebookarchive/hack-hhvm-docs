<?hh

class NullableNotAppropriate {
  public function noNullableNeeded(): ?int {
    $vec = Vector{3};
    return $vec->count();
  }
}

function main_nt() {
  $nna = new NullableNotAppropriate();
  $y = $nna->noNullableNeeded();
  var_dump($y);
}

main_nt();
