<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT);
require_once "PEAR/PackageFileManager2.php";

PEAR::setErrorHandling(PEAR_ERROR_DIE);

$packagexml = new PEAR_PackageFileManager2;
$packagexml->setOptions(array(
  "outputdirectory"      => ".",
  "filelistgenerator"    => "file",
  "packagefile"          => "package_pman.xml",
  "packagedirectory"     => "output/",
  "baseinstalldir"       => "php.net/",
  "simpleoutput"         => true,
  "roles"                => array("sh" => "script",),
  "dir_roles"            => array("man3" => "doc",),

  // This will unfortunately also exclude DOMDocument.xinclude, there is no way 
  // of doing case-sensitive ignores :(
  "ignore"               => array("index.sqlite", "man3/DomDocument.xinclude.3.gz"),
));

$packagexml->setPackage("pman");
$packagexml->setSummary("PHP Unix manual pages");
$packagexml->setDescription("Unix manual pages of the PHP documentations from php.net.");

$packagexml->setChannel("doc.php.net");
$packagexml->setAPIVersion("1.0.0");
$packagexml->setReleaseVersion(date("Y.m.d"));
$packagexml->setReleaseStability("stable");
$packagexml->setAPIStability("stable");
$packagexml->setLicense("Creative Commons Attribution 3.0", "http://creativecommons.org/licenses/by/3.0/");

$packagexml->setNotes("New release \o/");
$packagexml->setPackageType("php");
$packagexml->addRelease();

$packagexml->setPhpDep("5.0.0");
$packagexml->setPearinstallerDep("1.9.0");
$packagexml->addExtensionDep("required", "zlib");

$packagexml->addMaintainer("lead", "phpdoc", "The PHP Documentation team", "phpdoc@lists.php.net");

rename("output/php-functions", "output/man3");
copy(__DIR__ . "/pman.sh", "output/pman.sh");

$packagexml->addReplacement("pman.sh", "pear-config", "@doc_dir@", "doc_dir");
$packagexml->addInstallAs("pman.sh", "pman");



$packagexml->generateContents();
$packagexml->writePackageFile();
rename("output/pman.sh", "pman.sh");
rename("output/man3", "man3");

$contents = file_get_contents("package_pman.xml");
$contents = str_replace(
  'baseinstalldir="php.net/" name="pman.sh"',
  'baseinstalldir="" name="pman.sh"',
  $contents
);
file_put_contents("package_pman.xml", $contents);
system("pear package package_pman.xml");

rename("man3", "output/php-functions");
unlink("pman.sh");


