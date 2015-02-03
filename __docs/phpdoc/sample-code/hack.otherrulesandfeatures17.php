<?php

function cufun1(string $x): string {
  return $x.$x;
}

function cufun2(): (function(string): string) {
  return function($x) { return $x.$x; };
}

class CUFunFunFun {
  public function testFun1() {
    var_dump(call_user_func('cufun1', "blah"));
  }

  public function testFun2() {
    $x = cufun2();
    var_dump($x('blah'));
  }
}

function main_cufff() {
  $f = new CUFunFunFun();
  $f->testFun1();
  $f->testFun2();
}

main_cufff();
