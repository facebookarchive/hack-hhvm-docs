<?hh
class Obj<Tk, Tv> implements ArrayAccess<Tk, Tv> {
    public function offsetSet(Tk $offset, Tv $value): this {
        var_dump(__METHOD__);
    }
    public function offsetExists(Tk $var): bool {
        var_dump(__METHOD__);
        if ($var == "foobar") {
            return true;
        }
        return false;
    }
    public function offsetUnset(Tk $var): this {
        var_dump(__METHOD__);
    }
    public function offsetGet(Tk $var): Tv {
        var_dump(__METHOD__);
        return "value";
    }
}

$obj = new Obj();

echo "Runs obj::offsetExists()\n";
var_dump(isset($obj["foobar"]));

echo "\nRuns obj::offsetExists() and obj::offsetGet()\n";
var_dump(empty($obj["foobar"]));

echo "\nRuns obj::offsetExists(), *not* obj:offsetGet() as there is nothing to get\n";
var_dump(empty($obj["foobaz"]));
?>
