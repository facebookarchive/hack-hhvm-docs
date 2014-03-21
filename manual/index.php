<?php
// $Id$
include_once $_SERVER['DOCUMENT_ROOT'] . '/include/prepend.inc';
// We only support English right now
$LANG = "en";
mirror_redirect("/manual/$LANG/index.php");
?>