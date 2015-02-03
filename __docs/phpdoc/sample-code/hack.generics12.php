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

function mbmixed(Mailbox<mixed> $mbm): void {
  // Put a string into the mixed Mailbox
  $mbm->put("Hello");
}

function main() {
  $m = mbint();
  // This function puts a string into the Mailbox
  mbmixed($m);
  // Now what was a Mailbox<int> becomes a Mailbox<string>. Probably not expected behavior.
  var_dump($m);
}

main();
