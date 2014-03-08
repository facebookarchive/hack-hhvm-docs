#!/bin/sh
MAN=`which man`
$MAN -M @doc_dir@/pman/ $*
