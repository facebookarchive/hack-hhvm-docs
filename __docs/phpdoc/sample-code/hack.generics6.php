<?hh
// Copyright 2004-present Facebook. All Rights Reserved.

// generic interface
interface Box<T> {
  public function add(T $item): void;
  public function remove(): T;
}

// generic trait
trait Commerce<T> {
  public function buy(T $item): void {
    echo 'Bought a '.get_class($item)."\n";
  }

  public function sell(T $item): void {
    echo 'Sold a '.get_class($item)."\n";
  }
}

// generic class that uses generic trait and implements generic interface
class BestBuy<T> implements Box<T> {
  protected Vector<T> $vec;
  private int $last;

  public function __construct() {
    $this->vec = Vector{};
    $this->last = -1;
  }

  use Commerce<T>;

  public function add(T $item): void {
    $this->vec->add($item);
    $this->last++;
  }

  public function remove(): T {
    $item = $this->vec->at($this->last);
    $this->vec->removeKey($this->last--);
    return $item;
  }
}

// For example purposes
abstract class Computer {}
class Apple extends Computer{}
class Lenovo extends Computer {}
class Dell extends Computer {}

function main_gti() {
  $store = new BestBuy();
  $store->add(new Lenovo());
  $store->add(new Apple());
  $store->add(new Dell());
  echo get_class($store->remove())."\n";
  $store->sell($store->remove());
}

main_gti();
