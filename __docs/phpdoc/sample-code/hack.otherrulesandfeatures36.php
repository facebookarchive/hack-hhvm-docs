<?hh

use MyVendor\SomeComponent\TargetNs as T;

// Create a new ReflectionClass
$r = new ReflectionClass(T\Foo::class);
// instead of
$r = new ReflectionClass('MyVendor\SomeComponent\TargetNs\Foo');
