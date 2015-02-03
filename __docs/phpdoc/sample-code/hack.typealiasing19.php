<?hh

class TypeDefsConv {
  public static function funcForSeconds(int $s): void {}
  public static function funcForMinutes(int $m): void {}
  public static function funcForHours(int $h): void {}

  public static function convertSecondsToMinutes(int $s): int {
    return $s/60;
  }
  public static function convertMinutesToHours(int $m): int {
    return $m/60;
  }
}
