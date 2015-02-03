<?hh

$v = Vector {
  Vector {42, 42, 42},
  Vector {42}
};

$v->map(meth_caller('Vector', 'count')); // returns Vector {3, 1}
