<?hh
$div = <div />;
$div->setAttribute('class', 1); // Type error
$div->getAttribute('class')->someMethod(); // Type error
$num = <ui:number value={new Exception()} />; // Type error
