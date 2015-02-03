<?hh

function foo(): void {
  $name = 'MyName';

  echo <<<'EOT'
My name is "$name". I am printing some text.
Now, I am printing some {$foo->bar[1]}.
This should not print a capital 'A': \x41
EOT;
}

foo();
