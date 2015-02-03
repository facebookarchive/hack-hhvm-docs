<?hh

require_once "File1.php";

function give_me_a_box(Color $color): :ui:box {
  return <ui:box color={ColorEnum::getColor($color)}>
    This is really a silly example. I hope you will not actually write code like this...
  </ui:box>;
}
