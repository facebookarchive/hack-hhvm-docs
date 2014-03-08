#!/bin/sh
# svn co http://svn.php.net/repository/phpdoc/modules/doc-en
# pear install doc.php.net/phd
# pear install doc.php.net/phd_php
# cd doc-en

# Set the path to PHP from environment or use which to discover it
if [ "$PHP" == "" ];
then
	PHP=$(which php 2>/dev/null)
fi

# Sets the path to PHD from environment or use which to discover it
if [ "$PHD" == "" ];
then
	PHD=$(which phd 2>/dev/null)
fi

# Sets the browser application from environment or falls back on open if it is found
if [ "$BROWSER" == "" ];
then
	BROWSER=$(which open 2>/dev/null)
fi

# Test for executability of PHP
if [ ! -x "$PHP" ];
then
	echo "Cannot execute PHP ($PHP) !"
	exit 1
fi

# Test for executability of PHD
if [ ! -x "$PHD" ];
then
    echo "Cannot execute $PHD, is PHD installed ?"
    exit 2
fi

# Configure Documentation
$PHP doc-base/configure.php  --enable-xml-details

# Generate Documentation
$PHD --docbook doc-base/.manual.xml --package PHP --format xhtml

# Opens a browser if it is appropriate to do so
if [ "$BROWSER" != "" ];
then
    if [ -f output/php-chunked-xhtml/index.html ];
    then
	    $BROWSER output/php-chunked-xhtml/index.html 2>/dev/null 1>/dev/null </dev/null &
	fi
fi
