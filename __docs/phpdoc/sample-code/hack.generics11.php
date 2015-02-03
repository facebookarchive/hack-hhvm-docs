<?hh

class Mailbox<T> {
  private ?T $data;

  public function __construct() {
    $this->data = null;
  }
  public function put(T $mail): void {
    $this->data = $mail;
  }

  public function check(): ?T {
    if ($this->data !== null) {
      return $this->data;
    }
    return null;
  }
}

function mbint(): Mailbox<int> {
  $mbi = new Mailbox();
  $mbi->put(3);
  return $mbi;
}

function mbmixed(Mailbox<mixed> $mbm): void {}

function main() {
  $m = mbint();
  mbmixed($m);
}
