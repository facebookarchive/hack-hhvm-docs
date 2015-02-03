<?php

abstract class DayOfWeek {
  const Sunday = 0;
  const Monday = 1;
  const Tuesday = 2;
  const Wednesday = 3;
  const Thursday = 4;
  const Friday = 5;
  const Saturday = 6;
}

function foo() {
  return DayOfWeek::Wednesday; // returns a 3
}
