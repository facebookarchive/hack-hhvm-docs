<?hh

function heredoc(): void {
  $foo = 3;
  $x = <<<MYHD
{$foo}
MYHD;
}

echo heredoc();
