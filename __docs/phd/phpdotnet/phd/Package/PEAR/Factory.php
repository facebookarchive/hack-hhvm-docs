<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_PEAR_Factory extends Format_Factory {
    private $formats = array(
        'xhtml'         => 'Package_PEAR_ChunkedXHTML',
        'bigxhtml'      => 'Package_PEAR_BigXHTML',
        'php'           => 'Package_PEAR_Web',
        'chm'           => 'Package_PEAR_CHM',
        'tocfeed'       => 'Package_PEAR_TocFeed',
    );

    /**
     * The package version
     */
    private $version = '@phd_pear_version@';

    public function __construct() {
        parent::setPackageName("PEAR");
        parent::setPackageVersion($this->version);
        parent::registerOutputFormats($this->formats);
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

