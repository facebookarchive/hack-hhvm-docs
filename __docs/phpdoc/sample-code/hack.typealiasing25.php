<?hh

newtype Color = int;
class ColorEnum extends Enum<Color> {
  const Color BLUE = 1;
  const Color RED = 2;
  const Color GREEN = 3;

  public static function getColor(Color $color) {
    switch ($color) {
      case 1: return "0000ff";
      case 2: return "ff0000";
      case 3: return "00ff00";
  }
}
