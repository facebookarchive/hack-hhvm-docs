--TEST--
CALS Table rendering#003
--FILE--
<?php
/*  $Id$ */

require "include/PhDReader.class.php";
require "include/PhDFormat.class.php";
require "formats/xhtml.php";

$reader = new PhDReader(dirname(__FILE__) ."/data/003.xml");
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
<div id="function.nl-langinfo" class="article">
<table border="5">
<h1 class="title">
nl_langinfo Constants
</h1>
<colgroup>

<col align="left" />
<col align="left" />
<thead valign="middle">
<tr valign="middle">
<th colspan="1">
Constant
</th>
<th colspan="1">
Description
</th>
</tr>

</thead>

<tbody valign="middle">
<tr valign="middle">
<td colspan="2" rowspan="1" align="center">
<em class="emphasis">
LC_TIME Category Constants
</em>
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
ABDAY_(1-7)
</td>
<td colspan="1" rowspan="1" align="left">
Abbreviated name of n-th day of the week.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
DAY_(1-7)
</td>
<td colspan="1" rowspan="1" align="left">
Name of the n-th day of the week (DAY_1 = Sunday).
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
ABMON_(1-12)
</td>
<td colspan="1" rowspan="1" align="left">
Abbreviated name of the n-th month of the year.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
MON_(1-12)
</td>
<td colspan="1" rowspan="1" align="left">
Name of the n-th month of the year.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
AM_STR
</td>
<td colspan="1" rowspan="1" align="left">
String for Ante meridian.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
PM_STR
</td>
<td colspan="1" rowspan="1" align="left">
String for Post meridian.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
D_T_FMT
</td>
<td colspan="1" rowspan="1" align="left">
String that can be used as the format string for
<a href="function.strftime.html">strftime</a>
 to represent time and date.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
D_FMT
</td>
<td colspan="1" rowspan="1" align="left">
String that can be used as the format string for
<a href="function.strftime.html">strftime</a>
 to represent date.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
T_FMT
</td>
<td colspan="1" rowspan="1" align="left">
String that can be used as the format string for
<a href="function.strftime.html">strftime</a>
 to represent time.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
T_FMT_AMPM
</td>
<td colspan="1" rowspan="1" align="left">
String that can be used as the format string for
<a href="function.strftime.html">strftime</a>
 to represent time in 12-hour format with ante/post meridian.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
ERA
</td>
<td colspan="1" rowspan="1" align="left">
Alternate era.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
ERA_YEAR
</td>
<td colspan="1" rowspan="1" align="left">
Year in alternate era format.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
ERA_D_T_FMT
</td>
<td colspan="1" rowspan="1" align="left">
Date and time in alternate era format (string can be used in
<a href="function.strftime.html">strftime</a>
).
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
ERA_D_FMT
</td>
<td colspan="1" rowspan="1" align="left">
Date in alternate era format (string can be used in
<a href="function.strftime.html">strftime</a>
).
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
ERA_T_FMT
</td>
<td colspan="1" rowspan="1" align="left">
Time in alternate era format (string can be used in
<a href="function.strftime.html">strftime</a>
).
</td>
</tr>

<tr valign="middle">
<td colspan="2" rowspan="1" align="center">
<em class="emphasis">
LC_MONETARY Category Constants
</em>
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
INT_CURR_SYMBOL
</td>
<td colspan="1" rowspan="1" align="left">
International currency symbol.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
CURRENCY_SYMBOL
</td>
<td colspan="1" rowspan="1" align="left">
Local currency symbol.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
CRNCYSTR
</td>
<td colspan="1" rowspan="1" align="left">
Same value as CURRENCY_SYMBOL.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
MON_DECIMAL_POINT
</td>
<td colspan="1" rowspan="1" align="left">
Decimal point character.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
MON_THOUSANDS_SEP
</td>
<td colspan="1" rowspan="1" align="left">
Thousands separator (groups of three digits).
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
MON_GROUPING
</td>
<td colspan="1" rowspan="1" align="left">
Like &#039;grouping&#039; element.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
POSITIVE_SIGN
</td>
<td colspan="1" rowspan="1" align="left">
Sign for positive values.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
NEGATIVE_SIGN
</td>
<td colspan="1" rowspan="1" align="left">
Sign for negative values.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
INT_FRAC_DIGITS
</td>
<td colspan="1" rowspan="1" align="left">
International fractional digits.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
FRAC_DIGITS
</td>
<td colspan="1" rowspan="1" align="left">
Local fractional digits.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
P_CS_PRECEDES
</td>
<td colspan="1" rowspan="1" align="left">
Returns 1 if CURRENCY_SYMBOL precedes a positive value.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
P_SEP_BY_SPACE
</td>
<td colspan="1" rowspan="1" align="left">
Returns 1 if a space separates CURRENCY_SYMBOL from a positive value.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
N_CS_PRECEDES
</td>
<td colspan="1" rowspan="1" align="left">
Returns 1 if CURRENCY_SYMBOL precedes a negative value.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
N_SEP_BY_SPACE
</td>
<td colspan="1" rowspan="1" align="left">
Returns 1 if a space separates CURRENCY_SYMBOL from a negative value.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
P_SIGN_POSN
</td>
<td colspan="1" rowspan="2" align="left" valign="middle">
<ul class="itemizedlist">
<li class="listitem">
<p class="simpara">

          Returns 0 if parentheses surround the quantity and currency_symbol.

</p>
</li>
<li class="listitem">
<p class="simpara">

         Returns 1 if the sign string precedes the quantity and currency_symbol.

</p>
</li>
<li class="listitem">
<p class="simpara">

         Returns 2 if the sign string follows the quantity and currency_symbol.

</p>
</li>
<li class="listitem">
<p class="simpara">

         Returns 3 if the sign string immediately precedes the currency_symbol.

</p>
</li>
<li class="listitem">
<p class="simpara">

         Returns 4 if the sign string immediately follows the currency_symbol.

</p>
</li>
</ul>
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
N_SIGN_POSN
</td>
</tr>

<tr valign="middle">
<td colspan="2" rowspan="1" align="center">
<em class="emphasis">
LC_NUMERIC Category Constants
</em>
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
DECIMAL_POINT
</td>
<td colspan="1" rowspan="1" align="left">
Decimal point character.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
RADIXCHAR
</td>
<td colspan="1" rowspan="1" align="left">
Same value as DECIMAL_POINT.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
THOUSANDS_SEP
</td>
<td colspan="1" rowspan="1" align="left">
Separator character for thousands (groups of three digits).
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
THOUSEP
</td>
<td colspan="1" rowspan="1" align="left">
Same value as THOUSANDS_SEP.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
GROUPING
</td>
<td colspan="1" rowspan="1" align="left">
</td>
</tr>

<tr valign="middle">
<td colspan="2" rowspan="1" align="center">
<em class="emphasis">
LC_MESSAGES Category Constants
</em>
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
YESEXPR
</td>
<td colspan="1" rowspan="1" align="left">
Regex string for matching &#039;yes&#039; input.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
NOEXPR
</td>
<td colspan="1" rowspan="1" align="left">
Regex string for matching &#039;no&#039; input.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
YESSTR
</td>
<td colspan="1" rowspan="1" align="left">
Output string for &#039;yes&#039;.
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
NOSTR
</td>
<td colspan="1" rowspan="1" align="left">
Output string for &#039;no&#039;.
</td>
</tr>

<tr valign="middle">
<td colspan="2" rowspan="1" align="center">
<em class="emphasis">
LC_CTYPE Category Constants
</em>
</td>
</tr>

<tr valign="middle">
<td colspan="1" rowspan="1" align="left">
CODESET
</td>
<td colspan="1" rowspan="1" align="left">
Return a string with the name of the character encoding.
</td>
</tr>

</tbody>
</colgroup>

</table>

</div>

