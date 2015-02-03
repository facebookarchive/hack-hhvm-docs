<?php

class InitializeMemberVariables {

  // Parent class constructor. Uses setup() implemented in children to
  // initialize member variables.
  public final function __construct() {
    var_dump(func_get_args());
    var_dump($this);
    call_user_func_array(array($this, 'setup'), func_get_args());
  }

  // Children override this method
  protected function setup() {}
}
