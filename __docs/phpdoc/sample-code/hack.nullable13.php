<?hh

class UserDefinedType {}

class NullableUserDefinedType {
  public function userDefinedTypeMayBeNull(?UserDefinedType $udt): string {
    if ($udt === null) {
      return "Null";
    } else {
      return "I'm Set";
    }
  }
}

function main_nt() {
  $nb = new NullableUserDefinedType();
  $x = $nb->userDefinedTypeMayBeNull(null);
  $y = $nb->userDefinedTypeMayBeNull(new UserDefinedType());
  var_dump($x);
  var_dump($y);
}

main_nt();
