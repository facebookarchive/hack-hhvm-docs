<?hh

class CustomInvariantException extends Exception {}

function custom_callback(string $format, ...): void {
  throw new CustomInvariantException(sprintf(func_get_args()));
}

invariant_callback_register('custom_callback');
invariant_violation('Failure');
