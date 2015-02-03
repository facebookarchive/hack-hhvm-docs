<?php

function foo_closure($adder_str) {
  return function($to_str) use ($adder_str) {
    return strlen($to_str) + strlen($adder_str);
  };
}

function main_closure_example() {
  $hello = foo_closure("Hello");
  $facebook = foo_closure("Facebook");
  $fox = foo_closure("Fox");

  echo $hello("World") . "\n";
  echo $facebook("World") . "\n";
  echo $fox("World") . "\n";
}

main_closure_example();
