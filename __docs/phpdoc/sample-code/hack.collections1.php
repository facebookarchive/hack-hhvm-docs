<?php

// Simple array with the values 1, 2, 3 and default
// indexed by integers, starting with 0
$arr1 = array(1, 2, 3);

// Another simple array with the values "a", "b", "c"
// and again default indexed by integers
$arr2 = array("a", "b", "c");

// Array with strings as both keys and values
$arr3 = array("a" => "aaa", "b" => "bbb", "c" => "ccc");

// Array containing the values 1, 2, 3 but now indexed
// by a mix of integer keys and string keys
$arr4 = array("foo" => 1, 73 => 2, "bar" => 3);

// Array having mixed-typed values, default indexed by
// integers.
$arr5 = array(1, "hello", array(2, 3), "goodbye");

// Dynamically grow arrays by just adding new values. The
// keys do not have to be sequential or of the same type.
$arr1[] = 4; // The key will be 3
$arr1[4] = 5;
$arr2["bap"] = 6;
$arr3["d"] = "ddd";
$arr4[] = "blah"; // The key will be 74
$arr5[9] = 3;
