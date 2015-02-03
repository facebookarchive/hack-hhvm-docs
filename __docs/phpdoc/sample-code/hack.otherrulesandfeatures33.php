<?hh

function heredoc(): void {
  $x = <<<MYHD
Hello, I am in here
MYHD;
}

echo heredoc();
