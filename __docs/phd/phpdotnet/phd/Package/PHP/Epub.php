<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_PHP_Epub extends Package_PHP_ChunkedXHTML
{
    protected $lastContent = null;
    protected $parentdir;

    protected $playOrder = 0;
    protected $manifest = '';
    protected $spine = '';

    protected $streams = array();

    protected $info = array(
        'book_id'       => '',
        'title'         => '',
        'identifier'    => '',
        'creator'       => '',
        'lang'          => '',
        'opf_file'      => '',
        'ncx_file'      => '',
        'epub_file'     => '',
    );

    public function __construct() {
        parent::__construct();
        $this->setExt('.xhtml');
        $this->registerFormatName("PHP-Epub");
    }

    public function update($event, $val = null) {
        switch($event) {
        case Render::INIT:
            $this->parentdir = Config::output_dir()
                . strtolower($this->getFormatName()) . DIRECTORY_SEPARATOR;

            if(!file_exists($this->parentdir) || is_file($this->parentdir)) {
                mkdir($this->parentdir, 0777, true) or die("Can't create the output directory");
            }

            $this->setOutputDir($this->parentdir . 'OPS' . DIRECTORY_SEPARATOR);

            if(!file_exists($this->getOutputDir()) || is_file($this->getOutputDir())) {
                mkdir($this->outputdir, 0777, true) or die("Can't create the cache directory");
            }

            $this->info = $this->initInfo();

            $this->openOPF($this->info, $this->getOutputDir());
            $this->openNCX($this->info, $this->getOutputDir());

            $this->createMimeTypeFile($this->parentdir);
            $this->createContainerFile($this->parentdir);
            $this->createBuildFile($this->parentdir, $this->info['epub_file']);
            $this->createCSSFile($this->getOutputDir());

            $this->loadVersionAcronymInfo();
            $this->postConstruct();

            break;
        case Render::FINALIZE:
            $this->closeOPF();
            $this->closeNCX();
            break;
        default:
            parent::update($event, $val);
        }
    }

    protected function initInfo() {
        $root = parent::getRootIndex();
        $lang = Config::language();
        return array(
            'book_id' => 'php-manual',
            'title' => $root['ldesc'],
            'identifier' => 'http://www.php.net/manual/' . $lang,
            'creator' => 'PHP Documentation Team',
            'lang' => $lang,
            'opf_file' => strtolower($this->getFormatName()) . '.opf',
            'ncx_file' => strtolower($this->getFormatName()) . '.ncx',
            'epub_file' => 'php-manual.epub',
        );
    }

    protected function createBuildFile($dir, $epub_file) {
        $build = <<<BUILD
#!/bin/bash
zip -0Xq  {$epub_file} mimetype
zip -Xr9Dq {$epub_file} *
BUILD;

        file_put_contents($dir . 'build.sh', $build);
        chmod($dir . 'build.sh', 0755);
    }

    protected function createMimeTypeFile($dir) {
        file_put_contents($dir . 'mimetype', 'application/epub+zip');
    }

    protected function createCSSFile($dir) {
        file_put_contents($dir . "style.css",
            $this->fetchStylesheet() . PHP_EOL . 'body { padding : 3px;}');
    }

    protected function createContainerFile($dir) {
        $root_file = 'OPS/' . $this->info['opf_file'];
        $meta_dir = $dir . 'META-INF' . DIRECTORY_SEPARATOR;
        if(!file_exists($meta_dir) || is_file($meta_dir)) {
            mkdir($meta_dir, 0777, true) or die("Can't create the META-INF directory");
        }

        $container = <<<CONTAINER
<?xml version="1.0" encoding="UTF-8" ?>
<container version="1.0" xmlns="urn:oasis:names:tc:opendocument:xmlns:container">
   <rootfiles>
      <rootfile full-path="{$root_file}" media-type="application/oebps-package+xml"/>
   </rootfiles>
</container>
CONTAINER;

        file_put_contents($meta_dir . 'container.xml', $container);
    }

    protected function openOPF($info, $dir) {
        $header = <<<OPF
<?xml version="1.0"?>
<package version="2.0" xmlns="http://www.idpf.org/2007/opf" unique-identifier="{$info['book_id']}">
  <metadata xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:opf="http://www.idpf.org/2007/opf">
    <dc:title>{$info['title']}</dc:title>
    <dc:language>{$info['lang']}</dc:language>
    <dc:identifier id="{$info['book_id']}" opf:scheme="URI">{$info['identifier']}</dc:identifier>
    <dc:creator opf:role="aut">{$info['creator']}</dc:creator>
  </metadata>
OPF;

        $this->streams['opf'] = fopen($dir . $info['opf_file'], 'w');
        fwrite($this->streams['opf'], $header);
    }

    protected function openNCX($info, $dir) {
        $header = <<<NCX
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE ncx PUBLIC "-//NISO//DTD ncx 2005-1//EN"
"http://www.daisy.org/z3986/2005/ncx-2005-1.dtd">

<ncx version="2005-1" xml:lang="en" xmlns="http://www.daisy.org/z3986/2005/ncx/">

  <head>
    <meta name="dtb:uid" content="{$info['identifier']}"/>
    <meta name="dtb:depth" content="1"/>
    <meta name="dtb:totalPageCount" content="0"/>
    <meta name="dtb:maxPageNumber" content="0"/>
  </head>

  <docTitle>
    <text>{$info['title']}</text>
  </docTitle>

  <docAuthor>
    <text>{$info['creator']}</text>
  </docAuthor>

  <navMap>

NCX;

        $this->streams['ncx'] = fopen($dir . $info['ncx_file'], 'w');
        fwrite($this->streams['ncx'], $header);
    }

    protected function closeOPF() {
        $this->manifest .= <<<MANIFEST
    <item id="ncx" href="{$this->info['ncx_file']}" media-type="application/x-dtbncx+xml"/>

MANIFEST;

        fwrite($this->streams['opf'], "\n  <manifest>\n{$this->manifest}  </manifest>");
        fwrite($this->streams['opf'], "\n  <spine toc=\"ncx\">\n{$this->spine}  </spine>");
        fwrite($this->streams['opf'], "\n</package>");
        fclose($this->streams['opf']);
    }

    protected function closeNCX() {
        fwrite($this->streams['ncx'], "  </navMap>\n</ncx>");
        fclose($this->streams['ncx']);
    }

    protected function appendMetaData($id, $title, $filename, $class, $child = false) {
        $navPoint = '';
        if ($this->flags & Render::OPEN) {
            $this->playOrder++;
            $this->manifest .= <<<MANIFEST
    <item id="{$id}" href="{$filename}" media-type="application/xhtml+xml"/>

MANIFEST;

            $this->spine .= <<<SPINE
    <itemref idref="{$id}" linear="yes"/>

SPINE;
            if ($child) {
                $navPoint = <<<NAV
      <navPoint class="{$class}" id="{$id}" playOrder="{$this->playOrder}">
        <navLabel><text>{$title}</text></navLabel>
        <content src="{$filename}"/>
      </navPoint>

NAV;
            } else {
                $navPoint = <<<NAV
    <navPoint class="{$class}" id="{$id}" playOrder="{$this->playOrder}">
      <navLabel><text>{$title}</text></navLabel>
      <content src="{$filename}"/>

NAV;
            }

        } elseif (($this->flags & Render::CLOSE) && !$child) {
            $navPoint = <<<NAV
      </navPoint>

NAV;
        }
        if ($navPoint) {
            fwrite($this->streams['ncx'], $navPoint);
        }
    }

    public function appendData($data) {
        if ($this->lastContent) {
            $this->appendMetaData(
                $this->lastContent['id'],
                $this->lastContent['title'],
                $this->lastContent['filename'],
                $this->lastContent['class'],
                $this->lastContent['child']
            );
        }
        $this->lastContent = null;
        return parent::appendData($data);
    }

    public function format_chunk($open, $name, $attrs, $props) {
        $this->collectContent($attrs, $name, true);
        return parent::format_chunk($open, $name, $attrs, $props);
    }

    public function format_container_chunk($open, $name, $attrs, $props) {
        $this->collectContent($attrs, $name, false);
        return parent::format_container_chunk($open, $name, $attrs, $props);
    }

    public function format_root_chunk($open, $name, $attrs) {
        $this->collectContent($attrs, $name, false);
        return parent::format_root_chunk($open, $name, $attrs);
    }

    public function format_imagedata($open, $name, $attrs) {
        $img = parent::format_imagedata($open, $name, $attrs);
        static $id = 0;
        if ($img) {
            preg_match('/src="([^"]*)"/', $img, $matches);
            if (isset($matches[1])) {
                $href = $matches[1];
                $filename = basename($href);
                $ext = substr($filename, strrpos($filename, '.') + 1);
                //FIXME Create a real id for images
                $image_id = 'image-' . ++$id;
                $this->manifest .= <<<MANIFEST
    <item id="{$image_id}" href="{$href}" media-type="image/{$ext}"/>

MANIFEST;
            }
        }
        return $img;
    }

    public function header($id) {
        $title = $this->getLongDescription($id);
        static $cssLinks = null;
        if ($cssLinks === null) {
            $cssLinks = $this->createCSSLinks();
        }
        return <<<XHTML
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
    <title>{$title}</title>
    <link rel="stylesheet" href="style.css" type="text/css" />
{$cssLinks}
  </head>
  <body>
XHTML;
    }

    public function footer($id) {
        return '</body></html>';
    }

    protected function collectContent($attrs, $class, $child) {
        if (isset($attrs[Reader::XMLNS_XML]['id'])) {
            $id = $attrs[Reader::XMLNS_XML]['id'];
            $this->lastContent = array(
                'id' => $id,
                'title' => $this->getShortDescription($id),
                'filename' => ($this->getFilename($id) ? $this->getFilename($id) : $id) . $this->getExt(),
                'class' => $class,
                'child' => $child,
            );
        }
    }

    protected function fetchStylesheet($name = null) {
        $stylesheet = file_get_contents("http://www.php.net/styles/site.css");
        if ($stylesheet) return $stylesheet;
        else {
            v("Stylesheet not fetched. Uses default rendering style.", E_USER_WARNING);
            return "";
        }
    }

    public function format_link($open, $name, $attrs, $props) {
        $link = parent::format_link($open, $name, $attrs, $props);
        // Add title attribute to external links so address can be seen in CHM files.
        $search = '`<a href="([^#"][^"]++)" class="link external">`';
        $replacement = '<a href="\1" class="link external" title="Link : \1">';
        $link = preg_replace($search, $replacement, $link);
        return $link;
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

