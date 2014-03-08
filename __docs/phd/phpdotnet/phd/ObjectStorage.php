<?php
namespace phpdotnet\phd;
/* $Id$ */

class ObjectStorage extends \SplObjectStorage
{
	public function attach($obj, $inf = array()) {
		if (!($obj instanceof Format)) {
			throw new \InvalidArgumentException(
                'Only classess inheriting ' . __NAMESPACE__ . '\\Format supported'
            );
		}
		if (empty($inf)) {
			$inf = array(
				\XMLReader::ELEMENT => $obj->getElementMap(),
				\XMLReader::TEXT    => $obj->getTextMap(),
			);
		}
		parent::attach($obj, $inf);
		return $obj;
	}
}


/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

