<?php
namespace phpdotnet\phd;
/* $Id$ */

/**
* Handles images (and other media objects) referenced in the docbook files.
* Copies over, returns proper filenames etc.
* Useful for xhtml output.
*
* @author Christian Weiske <cweiske@php.net>
*/
class MediaManager
{
    /**
    * Directory where files are put
    *
    * @var string
    */
    public $output_dir = null;

    /**
    * Path the media files are referenced relative to in html.
    * Trailing slash required.
    *
    * @var string
    */
    public $relative_ref_path = '';

    /**
    * Path the media files are referenced relative to in the xml file.
    * This is nearly always the directory the xml file is located in.
    *
    * @var string
    */
    public $relative_source_path = '';

    /**
    * If the image media directory exists
    *
    * @var boolean
    */
    protected $media_dir_exists = false;



    public function __construct($relative_source_path)
    {
        $this->relative_source_path = rtrim($relative_source_path, '/\\') . '/';
    }//public function __construct(..)



    /**
    * Handles the file:
    * - Generate proper filename (short version with only one directory)
    * - Copy file over to this directory
    * - Return filename relative to output directory
    *
    * @param string $filename File name relative to docbook document root
    *
    * @return string New file path that should be used in xhtml document
    */
    public function handleFile($filename)
    {
        $basename = basename($filename);
        $newname  = md5(substr($filename, 0, -strlen($basename))) . '-' . $basename;
        //FIXME: make images dynamic according to file type (e.g. video)
        $newpath  = 'images/' . $newname;

        $this->copyOver($filename, $newpath);

        return $this->relative_ref_path . $newpath;
    }//public function handleFile(..)



    /**
    * Copies the file referenced with $filename into
    * $output_dir/$newpath.
    *
    * @param string $filename Original filename
    * @param string $newpath  New path relative to output directory
    *
    * @return void
    */
    protected function copyOver($filename, $newpath)
    {
        $fullpath = $this->output_dir . '/' . $newpath;
        if ($fullfilename = $this->findFile($filename)) {
            if (!$this->media_dir_exists) {
                $dir = dirname($fullpath);
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $this->media_dir_exists = true;
            }

            if (!copy($fullfilename, $fullpath)) {
                trigger_error('Image could not be copied to : ' . $fullfilename, E_USER_WARNING);
            }
        } else {
	    trigger_error("Image does not exist : $filename", E_USER_WARNING);
        }
    }//protected function copyOver(..)

    /**
    * Find the exact location of the file referenced with $filename
    *
    * If the file cannot be found using the supplied filename, which may be
    * based upon a specific language, then fallback to the English translation.
    *
    * @param string  $filename      Original filename
    * @param boolean $allowfallback If the required file cannot be found then fallback to English
    *
    * @return string Exact location of the file referenced with $filename or False if file not found.
    */
    public function findFile($filename, $allowfallback = true)
    {
        $sourcefilenames = array (
            // Original format where @LANG@ was part of phpdoc (ala peardoc).
            array('', $filename),

            // Where phpdoc/modules/doc-@LANG@ is used.
            array('../', $filename),

            // Where phpdoc/doc-base/trunks and phpdoc/en/trunk are used.
            array('../../', (substr($filename, 0, strpos($filename, '/', 1)) . '/trunk' . substr($filename, strpos($filename, '/', 1)))),
        );

        $foundfile = false;
        foreach($sourcefilenames as $pathoffset => $filenameinfo) {
            // Look for current language specific file.
            if (file_exists($testingfile = $this->relative_source_path . $filenameinfo[0] . $filenameinfo[1])) {
                $foundfile = $testingfile;
                break;
            }

            // Fallback to English version.
            if ($allowfallback && file_exists($testingfile = $this->relative_source_path . $filenameinfo[0] . 'en' . substr($filenameinfo[1], strpos($filenameinfo[1], '/')))) {
                $foundfile = $testingfile;
                break;
            }
        }

        return $foundfile;
    }//protected function findFile(..)

}//class MediaManager

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

