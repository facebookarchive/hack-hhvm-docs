<?hh

use MyVendor\SomeComponent\TargetEntityNs as Entity;

// Example fetching a user
$user = $entityManager->find(Entity\User::class, 5);
// instead of
$user = $entityManager->find('MyVendor\SomeComponent\TargetEntityNs\User', 5);
