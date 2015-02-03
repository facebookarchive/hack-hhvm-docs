<?hh

function main_tup() {
    $arr = array('3', 2, 3, 4, 'hi');
    $tup = tuple('3', 2, 3, 4, 'hi');
    var_dump($arr);
    var_dump($tup);
    // Change the 5th element of both. Perfectly fine.
    $arr[4] = 'bye';
    $tup[4] = 'bye';
    var_dump($arr);
    var_dump($tup);
    // Add a 6th element of both. Not fine. Type checker balks with tuple.
    $arr[5] = 'Good!';
    $tup[5] = 'Whoops!';
    var_dump($arr);
    var_dump($tup);
}

main_tup();
