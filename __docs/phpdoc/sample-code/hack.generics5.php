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
