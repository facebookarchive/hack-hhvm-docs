<?hh

// The mailbox can contain any type, but, per instantiation, once associated
// with a type, it cannot change. Mailbox<int>, Mailbox<string>, Mailbox<mixed>
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

function mbgen<T>(Mailbox<T> $mbm, T $item): void {
  $mbm->put($item);
}

function main() {
  $m = mbint();
  mbgen($m, 4);
  var_dump($m);
}

main();
