--TEST--
CALS Table rendering#002
--FILE--
<?php
/*  $Id$ */

require "include/PhDReader.class.php";
require "include/PhDFormat.class.php";
require "formats/xhtml.php";

$reader = new PhDReader(dirname(__FILE__) ."/data/002.xml");
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
<div id="function.db2-set-option" class="article">
<table border="5">
<h1 class="title">
Resource-Parameter Matrix
</h1>
<colgroup>

<col align="center" />
<col align="center" />
<col align="center" />
<col align="center" />
<col align="center" />
<thead valign="middle">
<tr valign="middle">
<th colspan="1">
Key
</th>
<th colspan="1">
Value
</th>
<th colspan="3">
Resource Type
</th>
</tr>

</thead>

<tbody valign="middle">
<tr valign="middle">
<td class="empty">&nbsp;</td><td class="empty">&nbsp;</td><td colspan="1" rowspan="1" align="left">
Connection
</td>
<td colspan="1" rowspan="1" align="left">
Statement
</td>
<td colspan="1" rowspan="1" align="left">
Result Set
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
autocommit
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
DB2_AUTOCOMMIT_ON
</span>
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
autocommit
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
DB2_AUTOCOMMIT_OFF
</span>
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
cursor
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
DB2_SCROLLABLE
</span>
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
cursor
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
DB2_FORWARD_ONLY
</span>
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
binmode
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
DB2_BINARY
</span>
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
binmode
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
DB2_CONVERT
</span>
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
binmode
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
DB2_PASSTHRU
</span>
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
db2_attr_case
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
DB2_CASE_LOWER
</span>
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
db2_attr_case
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
DB2_CASE_UPPER
</span>
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
db2_attr_case
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
DB2_CASE_NATURAL
</span>
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
deferred_prepare
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
DB2_DEFERRED_PREPARE_ON
</span>
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
deferred_prepare
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
DB2_DEFERRED_PREPARE_OFF
</span>
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
i5_fetch_only
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
DB2_I5_FETCH_ON
</span>
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
i5_fetch_only
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
DB2_I5_FETCH_OFF
</span>
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
userid
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
SQL_ATTR_INFO_USERID
</span>
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
acctstr
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
SQL_ATTR_INFO_ACCTSTR
</span>
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
applname
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
SQL_ATTR_INFO_APPLNAME
</span>
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
wrkstnname
</td>
<td colspan="1" rowspan="1" align="left">
<span class="literal">
SQL_ATTR_INFO_WRKSTNNAME
</span>
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
X
</td>
<td colspan="1" rowspan="1" align="left">
-
</td>
</tr>

</tbody>
</colgroup>

</table>

</div>

