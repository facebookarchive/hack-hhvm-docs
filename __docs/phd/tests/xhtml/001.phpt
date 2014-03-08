--TEST--
CALS Table rendering
--FILE--
<?php
/*  $Id$ */

require "include/PhDReader.class.php";
require "include/PhDFormat.class.php";
require "formats/xhtml.php";

$reader = new PhDReader(dirname(__FILE__) ."/data/001-1.xml");
$format = new XHTMLPhDFormat($reader, array(), array());

$map = $format->getMap();

while($reader->read()) {
    $type = $reader->nodeType;
    $name = $reader->name;

    switch($type) {
    case XMLReader::ELEMENT:
    case XMLReader::END_ELEMENT:
        $open = $type == XMLReader::ELEMENT;

        $funcname = "format_$name";
        if (isset($map[$name])) {
            $tag = $map[$name];
            if (is_array($tag)) {
                $tag = $reader->notXPath($tag);
            }
            if (strncmp($tag, "format_", 7)) {
                $retval = $format->transformFromMap($open, $tag, $name);
                break;
            }
            $funcname = $tag;
        }

        $retval = $format->{$funcname}($open, $name);
        break;

    case XMLReader::TEXT:
        $retval = htmlspecialchars($reader->value, ENT_QUOTES);
        break;

    case XMLReader::CDATA:
        $retval = $format->CDATA($reader->value);
        break;

    case XMLReader::COMMENT:
    case XMLReader::WHITESPACE:
    case XMLReader::SIGNIFICANT_WHITESPACE:
    case XMLReader::DOC_TYPE:
        /* swallow it */
        continue 2;

    default:
        trigger_error("Don't know how to handle {$name} {$type}", E_USER_ERROR);
        return;
    }
    echo $retval, "\n";
}

$reader->close();
?>
--EXPECT--
<div id="" class="article">
<h1 class="title">
Example table
</h1>
<table border="5">
<h1 class="title">
Sample CALS Table
</h1>
<colgroup>

<col align="left" />
<col align="left" />
<col align="left" />
<col align="left" />
<thead valign="middle">
<tr valign="middle">
<th colspan="2">
Horizontal Span
</th>
<th colspan="1">
a3
</th>
<th colspan="1">
a4
</th>
<th colspan="1">
a5
</th>
</tr>

</thead>

<tfoot valign="middle">
<tr valign="middle">
<th colspan="1">
f1
</th>
<th colspan="1">
f2
</th>
<th colspan="1">
f3
</th>
<th colspan="1">
f4
</th>
<th colspan="1">
f5
</th>
</tr>

</tfoot>

<tbody valign="middle">
<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
b1
</td>
<td colspan="1" rowspan="1" align="left">
b2
</td>
<td colspan="1" rowspan="1" align="left">
b3
</td>
<td colspan="1" rowspan="1" align="left">
b4
</td>
<td colspan="1" rowspan="2" align="left" valign="middle">
<p class="para">
Vertical Span
</p>
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
c1
</td>
<td colspan="2" rowspan="2" align="center" valign="bottom">
Span Both
</td>
<td colspan="1" rowspan="1" align="left">
c4
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
d1
</td>
<td colspan="1" rowspan="1" align="left">
d4
</td>
<td colspan="1" rowspan="1" align="left">
d5
</td>
</tr>

</tbody>
</colgroup>

</table>

</div>
