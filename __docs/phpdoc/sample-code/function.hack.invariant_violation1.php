<?hh

function f(): int {
  if (the_world_has_ended()) {
    invariant_violation('The world has ended, how is this script running?');
    // No need to return anything here, the type checker knows we can't get here.
  } else {
    return 1;
  }

  // No need to return anything here either, since the type checker knows all
  // relevant cases are covered above.
}
