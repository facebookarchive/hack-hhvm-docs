<?hh

newtype Seconds = int;
newtype Minutes = int;
newtype Hours = int;

class TypeDefsConv {
  public static function funcForSeconds(Seconds $s): void {}
  public static function funcForMinutes(Minutes $m): void {}
  public static function funcForHours(Hours $h): void {}

  public static function convertSecondsToMinutes(Seconds $s): Minutes {
    return (int)($s/60);
  }
  public static function convertMinutesToHours(Minutes $m): Hours {
    return (int)($m/60);
  }
}
