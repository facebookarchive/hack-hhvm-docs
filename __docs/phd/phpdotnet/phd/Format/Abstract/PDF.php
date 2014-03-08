<?php
namespace phpdotnet\phd;
/* $Id$ */

abstract class Format_Abstract_PDF extends Format {
    protected $pdfDoc;

    public function getPdfDoc() {
        return $this->pdfDoc;
    }

    public function setPdfDoc($pdfDoc) {
        $this->pdfDoc = $pdfDoc;
    }

    public function UNDEF($open, $name, $attrs, $props) {
        if ($open) {
            trigger_error("No mapper found for '{$name}'", E_USER_WARNING);
        }
        $this->pdfDoc->setFont(PdfWriter::FONT_NORMAL, 14, array(1, 0, 0)); // Helvetica 14 red
        $this->pdfDoc->appendText(($open ? "<" : "</") . $name . ">");
        $this->pdfDoc->revertFont();
        return "";
    }

    public function CDATA($str) {
        $this->pdfDoc->appendText(utf8_decode(trim($str)));
        return "";
    }

    public function transformFromMap($open, $tag, $name, $attrs, $props) {
        return "";
    }

    public function createLink($for, &$desc = null, $type = Format::SDESC){}

    public function TEXT($str) {}

}

class PdfWriter {
    // Font type constants (for setFont())
    const FONT_NORMAL = 0x01;
    const FONT_ITALIC = 0x02;
    const FONT_BOLD = 0x03;
    const FONT_VERBATIM = 0x04;
    const FONT_VERBATIM_ITALIC = 0x05;
    const FONT_MANUAL = 0x06;

    // "Objects" constants (for add())
    const PARA = 0x10;
    const INDENTED_PARA = 0x11;
    const TITLE = 0x12;
    const DRAW_LINE = 0x13;
    const LINE_JUMP = 0x14;
    const PAGE = 0x15;
    const TITLE2 = 0x16;
    const VERBATIM_BLOCK = 0x17;
    const ADMONITION = 0x18;
    const ADMONITION_CONTENT = 0x19;
    const END_ADMONITION = 0x1A;
    const URL_ANNOTATION = 0x1B;
    const LINK_ANNOTATION = 0x1C;
    const ADD_BULLET = 0x1D;
    const FRAMED_BLOCK = 0x1E;
    const END_FRAMED_BLOCK = 0x1F;
    const TITLE3 = 0x20;
    const TABLE = 0x21;
    const TABLE_ROW = 0x22;
    const TABLE_ENTRY = 0x23;
    const TABLE_END_ENTRY = 0x24;
    const END_TABLE = 0x25;
    const TABLE_END_ROW = 0x26;
    const ADD_NUMBER_ITEM = 0x27;
    const IMAGE = 0x28;

    // Page format
    const VMARGIN = 56.7; // = 1 centimeter
    const HMARGIN = 56.7; // = 1 centimeter
    const LINE_SPACING = 2; // nb of points between two lines
    const INDENT_SPACING = 10; // nb of points for indent
    const DEFAULT_SHIFT = 20; // default value (points) for shifted paragraph
    private $SCALE; // nb of points for 1 centimeter
    private $PAGE_WIDTH; // in points
    private $PAGE_HEIGHT; // in points

    private $haruDoc;
    private $pages = array();
    private $currentPage;
    private $currentPageNumber;
    private $currentBookName;

    private $currentFont;
    private $currentFontSize;
    private $currentFontColor;
    private $fonts;
    private $oldFonts = array();
    private $text;

    private $vOffset = 0;
    private $hOffset = 0;
    private $lastPage = array(
        "vOffset" => 0,
        "hOffset" => 0,
    );
    private $permanentLeftSpacing = 0;
    private $permanentRightSpacing = 0;

    private $appendToBuffer = false;
    // To append afterwards
    private $buffer = array(
        /* array(
            'text'       => "",
            'font'       => "",
            'size'       => "",
            'color'      => "",
        )*/
    );

    private $current = array(
        "leftSpacing"       => 0,
        "rightSpacing"      => 0,
        "oldVPosition"      => 0,
        "vOffset"           => 0,
        "newVOffset"        => 0,
        "pages"             => array(),
        "row"               => array(),
        "align"             => "",
        "char"              => "",
        "charOffset"        => 0,
    );

    // To temporarily store $current(s)
    private $old = array();

    function __construct($pageWidth = 210, $pageHeight = 297) {
    	// Initialization of properties
    	$this->haruDoc = new \HaruDoc;
    	$this->haruDoc->addPageLabel(1, \HaruPage::NUM_STYLE_DECIMAL, 1, "Page ");

        $this->haruDoc->setPageMode(\HaruDoc::PAGE_MODE_USE_OUTLINE);
        $this->haruDoc->setPagesConfiguration(2);

    	// Page format
    	$this->SCALE = 72/25.4;
    	$this->PAGE_WIDTH = $pageWidth * $this->SCALE;
    	$this->PAGE_HEIGHT = $pageHeight * $this->SCALE;

    	// Set fonts
    	$this->fonts["Helvetica"] = $this->haruDoc->getFont("Helvetica", "WinAnsiEncoding");
    	$this->fonts["Helvetica-Bold"] = $this->haruDoc->getFont("Helvetica-Bold", "WinAnsiEncoding");
    	$this->fonts["Helvetica-Oblique"] = $this->haruDoc->getFont("Helvetica-Oblique", "WinAnsiEncoding");
    	$this->fonts["Courier"] = $this->haruDoc->getFont("Courier", "WinAnsiEncoding");
    	$this->fonts["Courier-Oblique"] = $this->haruDoc->getFont("Courier-Oblique", "WinAnsiEncoding");

    	// Add first page and default font settings
    	$this->currentFont = $this->fonts["Helvetica"];
    	$this->currentFontSize = 12;
    	$this->currentFontColor = array(0, 0, 0); // Black
    	$this->nextPage();
    	$this->haruDoc->addPageLabel(1, \HaruPage::NUM_STYLE_DECIMAL, 1, "Page ");
    }

    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function setCompressionMode($mode) {
        $this->haruDoc->setCompressionMode($mode);
    }

    // Append text into the current position
    public function appendText($text) {
//        if ($this->vOffset > $this->current["charOffset"] + 3*LINE_SPACING + 3*$this->currentFontSize)
//            $this->vOffset = $this->current["charOffset"] + 3*LINE_SPACING + 3*$this->currentFontSize;
        if ($this->appendToBuffer) {
            array_push($this->buffer, array(
                "text" => $text,
                "font" => $this->currentFont,
                "size" => $this->currentFontSize,
                "color" => $this->currentFontColor
            ));
            return;
        }

        $this->currentPage->beginText();
        do {
            // Clear the whitespace if it begins the line or if last char is a special char
            if (strpos($text, " ") === 0 && ($this->hOffset == 0 || in_array($this->current["char"], array("&", "$")))) {
                $text = substr($text, 1);
            }

            // Number of chars allowed in the current line
            $nbCarac = $this->currentFont->measureText($text,
                ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->hOffset - $this->permanentLeftSpacing - $this->permanentRightSpacing),
                $this->currentFontSize, $this->currentPage->getCharSpace(),
                $this->currentPage->getWordSpace(), true);

            // If a the text content can't be appended (either there is no whitespaces,
            // either the is not enough space in the line)
            if ($nbCarac === 0) {
                $isEnoughSpaceOnNextLine = $this->currentFont->measureText($text,
                    ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->permanentLeftSpacing - $this->permanentRightSpacing),
                    $this->currentFontSize, $this->currentPage->getCharSpace(),
                    $this->currentPage->getWordSpace(), true);
                if ($isEnoughSpaceOnNextLine) {
                    $this->vOffset += $this->currentFontSize + self::LINE_SPACING;
                    $this->hOffset = 0;
                    $isLastLine = false;
                    continue;
                } else {
                    $nbCarac = $this->currentFont->measureText($text,
                        ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->hOffset - $this->permanentLeftSpacing - $this->permanentRightSpacing),
                        $this->currentFontSize, $this->currentPage->getCharSpace(),
                        $this->currentPage->getWordSpace(), false);
                }
            }
            $isLastLine = ($nbCarac == strlen($text));

            $textToAppend = substr($text, 0, $nbCarac);
            $text = substr($text, $nbCarac);

            // Append text (in a new page if needed) with align
            if ($this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset) < self::VMARGIN) {
                $this->currentPage->endText();
                $this->current["pages"][] = $this->currentPage;
                $this->nextPage();
                $this->currentPage->beginText();
            }
            if ($this->current["align"] == "center") {
                $spacing = $this->PAGE_WIDTH - 2*self::HMARGIN -
                    $this->permanentLeftSpacing - $this->permanentRightSpacing - $this->currentPage->getTextWidth($textToAppend);
                $this->currentPage->textOut(self::HMARGIN + $this->hOffset + $this->permanentLeftSpacing + $spacing/2,
                    $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset), $textToAppend);
            } elseif ($this->current["align"] == "right") {
                $spacing = $this->PAGE_WIDTH - 2*self::HMARGIN -
                    $this->permanentLeftSpacing - $this->permanentRightSpacing - $this->currentPage->getTextWidth($textToAppend);
                $this->currentPage->textOut(self::HMARGIN + $this->hOffset + $this->permanentLeftSpacing + $spacing,
                    $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset), $textToAppend);
            } else { // left
                $this->currentPage->textOut(self::HMARGIN + $this->hOffset + $this->permanentLeftSpacing,
                    $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset), $textToAppend);
            }
            if ($textToAppend)
                $this->current["char"] = $textToAppend{strlen($textToAppend)-1};

            // Offsets for next line
            if (!$isLastLine) {
                $this->vOffset += $this->currentFontSize + self::LINE_SPACING;
                $this->hOffset = 0;
            } else {
                $this->hOffset += $this->currentPage->getTextWidth($textToAppend);
            }

        }
        while(!$isLastLine); // While it remains chars to append
        $this->currentPage->endText();
        $this->current["charOffset"] = $this->vOffset;
    }

    // Same function one line at a time
    public function appendOneLine($text) {
        if (strpos($text, " ") === 0 && ($this->hOffset == 0 || in_array($this->current["char"], array("&", "$")))) {
            $text = substr($text, 1);
        }

        $this->currentPage->beginText();
        $nbCarac = $this->currentFont->measureText($text,
            ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->hOffset - $this->permanentLeftSpacing - $this->permanentRightSpacing),
            $this->currentFontSize, $this->currentPage->getCharSpace(),
            $this->currentPage->getWordSpace(), true);

        // If a the text content can't be appended (either there is no whitespaces,
        // either the is not enough space in the line)
        if ($nbCarac === 0) {
            $isEnoughSpaceOnNextLine = $this->currentFont->measureText($text,
                ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->permanentLeftSpacing - $this->permanentRightSpacing),
                $this->currentFontSize, $this->currentPage->getCharSpace(),
                $this->currentPage->getWordSpace(), true);
            if ($isEnoughSpaceOnNextLine) {
                $this->currentPage->endText();
                return $text;
            } else {
                $nbCarac = $this->currentFont->measureText($text,
                    ($this->PAGE_WIDTH - 2*self::HMARGIN - $this->hOffset - $this->permanentLeftSpacing - $this->permanentRightSpacing),
                    $this->currentFontSize, $this->currentPage->getCharSpace(),
                    $this->currentPage->getWordSpace(), false);
            }
        }

        $isLastLine = ($nbCarac == strlen($text));

        $textToAppend = substr($text, 0, $nbCarac);
        $text = substr($text, $nbCarac);

        // Append text (in a new page if needed)
        if ($this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset) < self::VMARGIN) {
            $this->currentPage->endText();
            $this->current["pages"][] = $this->currentPage;
            $this->nextPage();
            $this->currentPage->beginText();
        }
        $this->currentPage->textOut(self::HMARGIN + $this->hOffset + $this->permanentLeftSpacing,
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset), $textToAppend);
        if ($textToAppend)
            $this->current["char"] = $textToAppend{strlen($textToAppend)-1};

        $this->hOffset += $this->currentPage->getTextWidth($textToAppend);

        $this->currentPage->endText();
        $this->current["charOffset"] = $this->vOffset;

        return ($isLastLine ? null : $text);
    }

    public function setAppendToBuffer($appendToBuffer) {
        $this->appendToBuffer = $appendToBuffer;
    }

    public function appendBufferNow() {
        foreach($this->buffer as $row) {
            if ($row["text"] == "\n") {
                $this->lineJump();
            } else {
                $this->setFont(self::FONT_MANUAL, $row["size"], $row["color"], $row["font"]);
                $this->appendText($row["text"]);
                $this->revertFont();
            }
        }
        $this->buffer = array();
    }

    public function add($type, $option = null) {
        if ($this->appendToBuffer) return;
        switch ($type) {
            case self::INDENTED_PARA:
                $this->lineJump();
                $this->indent();
                break;
            case self::PARA:
                $this->lineJump();
                break;
            case self::VERBATIM_BLOCK:
                $this->setFont(self::FONT_VERBATIM, 10, array(0.3, 0.3, 0.3));
                $this->lineJump();
                break;
            case self::TITLE:
                $this->setFont(self::FONT_BOLD, 20);
                $this->lineJump();
                break;
            case self::TITLE2:
                $this->setFont(self::FONT_BOLD, 14);
                $this->lineJump();
                break;
            case self::TITLE3:
                $this->setFont(self::FONT_BOLD, 12);
                $this->lineJump();
                break;
            case self::DRAW_LINE:
                $this->traceLine($option);
                break;
            case self::LINE_JUMP:
                if ($option)
                    $this->lineJump($option);
                else $this->lineJump();
                break;
            case self::PAGE:
                $this->nextPage();
                break;
            case self::ADMONITION:
                $this->beginAdmonition();
                break;
            case self::ADMONITION_CONTENT:
                $this->admonitionContent();
                break;
            case self::END_ADMONITION:
                $this->endAdmonition();
                break;
            case self::URL_ANNOTATION:
                $this->appendUrlAnnotation($option[0], $option[1]);
                break;
            case self::LINK_ANNOTATION:
                return $this->prepareInternalLinkAnnotation($option);
                break;
            case self::ADD_BULLET:
                $this->indent(-self::INDENT_SPACING-$this->currentPage->getTextWidth(chr(149)));
                $this->appendText(chr(149)); // ANSI Bullet
                $this->indent(0);
                break;
            case self::ADD_NUMBER_ITEM:
                $this->indent(-self::INDENT_SPACING-$this->currentPage->getTextWidth($option));
                $this->appendText($option);
                $this->indent(0);
                break;
            case self::FRAMED_BLOCK:
                $this->beginFrame();
                break;
            case self::END_FRAMED_BLOCK:
                $this->endFrame($option);
                break;
            case self::TABLE:
                $this->addTable($option);
                break;
            case self::END_TABLE:
                $this->endTable();
                break;
            case self::TABLE_ROW:
                $this->newTableRow($option[0], $option[1]);
                break;
            case self::TABLE_END_ROW:
                $this->endTableRow();
                break;
            case self::TABLE_ENTRY:
                $this->beginTableEntry($option[0], $option[1], $option[2]);
                break;
            case self::TABLE_END_ENTRY:
                $this->endTableEntry();
                break;
            case self::IMAGE:
                $this->addImage($option);
                break;
            default:
                trigger_error("Unknown object type : {$type}", E_USER_WARNING);
                break;
        }
    }

    // Switch font on-the-fly
    public function setFont($type, $size = null, $color = null, $font = null) {
        if ($this->currentPage == null)
            return false;
        $this->oldFonts[] = array($this->currentFont, $this->currentFontSize, $this->currentFontColor);
        $this->currentFontSize = ($size ? $size : $this->currentFontSize);
        if ($color && count($color) === 3) {
            $this->setColor($color[0], $color[1], $color[2]);
            $this->currentFontColor = $color;
        }
        else
            $this->setColor($this->currentFontColor[0], $this->currentFontColor[1], $this->currentFontColor[2]);
        switch ($type) {
            case self::FONT_NORMAL:
                $this->currentPage->setFontAndSize($this->currentFont = $this->fonts["Helvetica"],
                    $this->currentFontSize);
                break;
            case self::FONT_ITALIC:
                $this->currentPage->setFontAndSize($this->currentFont = $this->fonts["Helvetica-Oblique"],
                    $this->currentFontSize);
                break;
            case self::FONT_BOLD:
                $this->currentPage->setFontAndSize($this->currentFont = $this->fonts["Helvetica-Bold"],
                    $this->currentFontSize);
                break;
            case self::FONT_VERBATIM:
                $this->currentPage->setFontAndSize($this->currentFont = $this->fonts["Courier"],
                    $this->currentFontSize);
                break;
            case self::FONT_VERBATIM_ITALIC:
                $this->currentPage->setFontAndSize($this->currentFont = $this->fonts["Courier-Oblique"],
                    $this->currentFontSize);
                break;
            case self::FONT_MANUAL:
                $this->currentPage->setFontAndSize($this->currentFont = $font, $this->currentFontSize);
                break;
            default:
                trigger_error("Unknown font type : {$type}", E_USER_WARNING);
                break;
        }
    }

    // Back to the last used font
    public function revertFont() {
        $lastFont = array_pop($this->oldFonts);
        $this->currentFont = $lastFont[0];
        $this->currentFontSize = $lastFont[1];
        $this->currentFontColor = $lastFont[2];
        $this->currentPage->setFontAndSize($lastFont[0], $lastFont[1]);
        $this->setColor($lastFont[2][0], $lastFont[2][1], $lastFont[2][2]);
    }

    // Change font color (1, 1, 1 = white, 0, 0, 0 = black)
    public function setColor($r, $g, $b) {
        if ($r < 0 || $r > 1 || $g < 0 || $g > 1 || $b < 0 || $b > 1)
            return false;
        $this->currentPage->setRGBStroke($r, $g, $b);
        $this->currentPage->setRGBFill($r, $g, $b);
        $this->currentFontColor = array($r, $g, $b);
        return true;
    }

    // Save the current PDF Document to a file
    public function saveToFile($filename) {
        $this->haruDoc->save($filename);
    }

    public function createOutline($description, $parentOutline = null, $opened = false) {
        $outline = $this->haruDoc->createOutline($description, $parentOutline);
        $dest = $this->currentPage->createDestination();
        $dest->setXYZ(0, $this->currentPage->getHeight(), 1);
        $outline->setDestination($dest);
        $outline->setOpened($opened);
        return $outline;
    }

    public function shift($offset = self::DEFAULT_SHIFT) {
        $this->permanentLeftSpacing += $offset;
    }

    public function unshift($offset = self::DEFAULT_SHIFT) {
        $this->permanentLeftSpacing -= $offset;
    }

    public function vOffset($offset) {
        $this->vOffset += $offset;
    }

    private function indent($offset = self::INDENT_SPACING) {
        $this->hOffset = $offset;
    }

    // Jump to next page (or create a new one if none exists)
    private function nextPage() {
        $this->lastPage = array(
            "vOffset" => $this->vOffset,
            "hOffset" => $this->hOffset,
        );
        $footerToAppend = false;
        $this->currentPageNumber++;
        if (isset($this->pages[$this->currentPageNumber])) {
            $this->currentPage = $this->pages[$this->currentPageNumber];
            $this->vOffset = $this->currentFontSize;
            $this->hOffset = 0;
        } else {
            $this->pages[$this->currentPageNumber] = $this->haruDoc->addPage();
            $this->currentPage = $this->pages[$this->currentPageNumber];
            $this->currentPage->setTextRenderingMode(\HaruPage::FILL);
            $this->vOffset = $this->currentFontSize;
            $this->hOffset = ($this->hOffset ? $this->hOffset : 0);
            $footerToAppend = true;
        }
        if ($this->currentFont && $this->currentFontSize && $this->currentFontColor) {
            $this->currentPage->setFontAndSize($this->currentFont, $this->currentFontSize);
            $this->setColor($this->currentFontColor[0], $this->currentFontColor[1], $this->currentFontColor[2]);
        }
        if ($footerToAppend && $this->currentPageNumber > 1) {
            $this->currentPage->beginText();
            $this->setFont(self::FONT_NORMAL, 12, array(0,0,0));
            $this->currentPage->textOut($this->PAGE_WIDTH - self::HMARGIN - $this->currentPage->getTextWidth($this->currentPageNumber),
                self::VMARGIN - 30, $this->currentPageNumber);
            $this->revertFont();
            $this->setFont(self::FONT_BOLD, 12, array(0,0,0));
            $this->currentPage->textOut(self::HMARGIN,
                self::VMARGIN - 30, $this->currentBookName);
            $this->revertFont();
            $this->currentPage->endText();

        }

    }

    public function setCurrentBookName($currentBookName) {
        $this->currentBookName = $currentBookName;
    }

    // Set last page as the current page
    private function lastPage() {
        $this->currentPageNumber--;
        $this->currentPage = $this->pages[$this->currentPageNumber];
        $this->vOffset = $this->lastPage["vOffset"];
        $this->hOffset = $this->lastPage["hOffset"];
    }

    // Returns true if a next page exists
    private function isNextPage() {
        return isset($this->pages[$this->currentPageNumber + 1]);
    }

    // Jump a line
    private function lineJump($nbLines = 1) {
        $this->vOffset += $nbLines * ($this->currentFontSize + self::LINE_SPACING);
        $this->hOffset = 0;
    }

    // Trace a line from the current position
    private function traceLine() {
        $this->lineJump();
        $this->currentPage->rectangle(self::HMARGIN + $this->hOffset, $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset, $this->PAGE_WIDTH - 2*$this->hOffset - 2*self::HMARGIN, 1);
        $this->currentPage->stroke();
    }

    private function beginAdmonition() {
        // If this admonition is inside another frame
        array_push($this->old, $this->current);

        $this->setFont(self::FONT_BOLD, 12);
        $this->lineJump();
        // If no space for admonition title + interleave + admonition first line on this page, then creates a new one
        if (($this->PAGE_HEIGHT - 2*self::VMARGIN - $this->vOffset) < (3*$this->currentFontSize + 3*self::LINE_SPACING))
            $this->nextPage();
        $this->current["vOffset"] = $this->vOffset;
        $this->lineJump();
        $this->permanentLeftSpacing += self::INDENT_SPACING;
        $this->permanentRightSpacing += self::INDENT_SPACING;
        $this->current["pages"] = array();
    }

    private function admonitionContent() {
        if ($this->current["pages"])
            $this->current["vOffset"] = 0;
        $this->beginFrame();
        $this->revertFont();
        $this->currentPage->rectangle(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING),
            $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset,
            $this->PAGE_WIDTH - 2*self::HMARGIN - ($this->permanentLeftSpacing - self::INDENT_SPACING) - ($this->permanentRightSpacing - self::INDENT_SPACING),
            $this->vOffset - $this->current["vOffset"]);
        $this->currentPage->stroke();
    }

    private function endAdmonition() {
        $this->endFrame();
        $this->permanentLeftSpacing -= self::INDENT_SPACING;
        $this->permanentRightSpacing -= self::INDENT_SPACING;
        $current = array_pop($this->old);
        $current["pages"] = array_merge($current["pages"], $this->current["pages"]);
        $this->current = $current;
    }

    private function beginFrame() {
        $this->lineJump();
        $this->current["newVOffset"] = $this->vOffset;
        $this->current["pages"] = array();
    }

    private function endFrame($dash = null) {
        $onSinglePage = true;
        foreach ($this->current["pages"] as $page) {
            $page->setRGBStroke(0, 0, 0);
            $page->setLineWidth(1.0);
            $page->setDash($dash, 0);
            // left border
            $page->moveTo(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING),
                self::VMARGIN);
            $page->lineTo(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING),
                $this->PAGE_HEIGHT - self::VMARGIN - $this->current["newVOffset"]);
            // right border
            $page->moveTo($this->PAGE_WIDTH - self::HMARGIN - ($this->permanentRightSpacing - self::INDENT_SPACING),
                self::VMARGIN);
            $page->lineTo($this->PAGE_WIDTH - self::HMARGIN - ($this->permanentRightSpacing - self::INDENT_SPACING),
                $this->PAGE_HEIGHT - self::VMARGIN - $this->current["newVOffset"]);
            $page->stroke();
            $page->setDash(null, 0);
            $this->current["newVOffset"] = 0;
            $onSinglePage = false;
        }
        $this->currentPage->setRGBStroke(0, 0, 0);
        $this->currentPage->setLineWidth(1.0);
        $this->currentPage->setDash($dash, 0);
        // left border
        $this->currentPage->moveTo(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING),
            $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset);
        $this->currentPage->lineTo(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING),
            $this->PAGE_HEIGHT - self::VMARGIN - $this->current["newVOffset"]);
        // right border
        $this->currentPage->moveTo($this->PAGE_WIDTH - self::HMARGIN - ($this->permanentRightSpacing - self::INDENT_SPACING),
            $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset);
        $this->currentPage->lineTo($this->PAGE_WIDTH - self::HMARGIN - ($this->permanentRightSpacing - self::INDENT_SPACING),
            $this->PAGE_HEIGHT - self::VMARGIN - $this->current["newVOffset"]);
        // bottom border
        $this->currentPage->moveTo(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING),
            $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset);
        $this->currentPage->lineTo($this->PAGE_WIDTH - self::HMARGIN - ($this->permanentRightSpacing - self::INDENT_SPACING),
            $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset);
        // top border (if frame's on a single page)
        if ($onSinglePage) {
            $this->currentPage->moveTo(self::HMARGIN + ($this->permanentLeftSpacing - self::INDENT_SPACING),
                $this->PAGE_HEIGHT - self::VMARGIN - $this->current["newVOffset"]);
            $this->currentPage->lineTo($this->PAGE_WIDTH - self::HMARGIN - ($this->permanentRightSpacing - self::INDENT_SPACING),
                $this->PAGE_HEIGHT - self::VMARGIN - $this->current["newVOffset"]);
        }

        $this->currentPage->stroke();
        $this->lineJump();
        $this->currentPage->setDash(null, 0);
        $this->current["oldVPosition"] = 0;
    }

    // Append $text with an underlined blue style with a link to $url
    private function appendUrlAnnotation($text, $url) {
        $this->appendText(" ");
        $fromHOffset = $this->hOffset;

        // If more than one text line to append
        while ($text = $this->appendOneLine($text)) {
            // Trace the underline
            $this->currentPage->setLineWidth(1.0);
            $this->currentPage->setDash(null, 0);
            $this->currentPage->moveTo(self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING));
            $this->currentPage->lineTo(self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING));
            $this->currentPage->stroke();

            // Create link
            $annotationArea = array(self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING),
                self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset - $this->currentFontSize));
            $this->currentPage->createURLAnnotation($annotationArea, $url)->setBorderStyle(0, 0, 0);

            // Prepare the next line
            $this->vOffset += $this->currentFontSize + self::LINE_SPACING;
            $this->hOffset = 0;
            $fromHOffset = $this->hOffset;
        }

        // Trace the underline
        $this->currentPage->setLineWidth(1.0);
        $this->currentPage->setDash(null, 0);
        $this->currentPage->moveTo(self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING));
        $this->currentPage->lineTo(self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING));
        $this->currentPage->stroke();

        // Create link
        $annotationArea = array(self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING),
            self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
            $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset - $this->currentFontSize));
        $this->currentPage->createURLAnnotation($annotationArea, $url)->setBorderStyle(0, 0, 0);

    }

    // Append $text with an underlined blue style and prepare an internal link (which will be resolved later)
    private function prepareInternalLinkAnnotation($text) {
        $this->appendText(" ");
        $fromHOffset = $this->hOffset;
        $linkAreas = array(/* page, left, bottom, right, top */);

        // If more than one text line to append
        while ($text = $this->appendOneLine($text)) {
           // Create link
            $linkAreas[] = array($this->currentPage,
                self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING),
                self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset - $this->currentFontSize));

            // Prepare the next line
            $this->vOffset += $this->currentFontSize + self::LINE_SPACING;
            $this->hOffset = 0;
            $fromHOffset = $this->hOffset;
        }

        // Prepare link
        $linkAreas[] = array($this->currentPage,
                self::HMARGIN + $this->permanentLeftSpacing + $fromHOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset + self::LINE_SPACING),
                self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
                $this->PAGE_HEIGHT - (self::VMARGIN + $this->vOffset - $this->currentFontSize));

        return $linkAreas;
    }

    public function resolveInternalLink($page, $rectangle, $destPage) {
        $page->setRGBStroke(0, 0, 1); // blue
        // Trace the underline
        $page->setLineWidth(1.0);
        $page->setDash(array(2), 1);
        $page->moveTo($rectangle[0], $rectangle[1]);
        $page->lineTo($rectangle[2], $rectangle[1]);
        $page->stroke();
        // Create link
        $page->createLinkAnnotation($rectangle, $destPage->createDestination())
            ->setBorderStyle(0, 0, 0);
        $page->setDash(null, 0);
    }

    public function addTable($colCount) {
        // If this table is inside another table or frame
        array_push($this->old, $this->current);
        $this->current["leftSpacing"] = $this->permanentLeftSpacing;
        $this->current["rightSpacing"] = $this->permanentRightSpacing;
        // First horizontal line
        $this->currentPage->moveTo(self::HMARGIN + $this->current["leftSpacing"], $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset);
        $this->currentPage->lineTo($this->PAGE_WIDTH - self::HMARGIN - $this->current["rightSpacing"], $this->PAGE_HEIGHT - self::VMARGIN - $this->vOffset);
        $this->currentPage->stroke();
    }

    public function endTable() {
        $this->permanentLeftSpacing = $this->current["leftSpacing"];
        $this->permanentRightSpacing = $this->current["rightSpacing"];
        $this->lineJump();
        $current = array_pop($this->old);
        $current["pages"] = array_merge($current["pages"], $this->current["pages"]);
        $this->current = $current;
    }

    public function newTableRow($colCount, $valign) {
        $this->current["vOffset"] = $this->vOffset;
        $this->current["row"]["cellCount"] = $colCount;
        $this->current["row"]["activeCell"] = 0;
        $this->current["row"]["hSize"] = ($this->PAGE_WIDTH - 2*self::HMARGIN -
            $this->current["leftSpacing"] - $this->current["rightSpacing"]) / $this->current["row"]["cellCount"];
        $this->current["row"]["vPosition"] = 0;
        $this->current["row"]["pages"] = array();
        $this->current["row"]["cutPolicy"] = array(1);
        $this->current["pages"] = array();
    }

    public function beginTableEntry($colspan, $rowspan, $align) {
        $this->permanentLeftSpacing = ($this->current["row"]["activeCell"]++) * $this->current["row"]["hSize"] +
            self::LINE_SPACING + $this->current["leftSpacing"];
        $this->permanentRightSpacing = $this->PAGE_WIDTH - 2*self::HMARGIN -
            ($this->current["row"]["activeCell"] + $colspan - 1) * $this->current["row"]["hSize"] -
            $this->current["leftSpacing"] + self::LINE_SPACING;

        foreach ($this->current["pages"] as $page) {
            $this->lastPage();
        }
        $this->current["pages"] = array();

        $this->hOffset = 0;
        $this->vOffset = $this->current["vOffset"] + $this->currentFontSize + self::LINE_SPACING;
        $this->current["align"] = $align;

        array_push($this->current["row"]["cutPolicy"], $colspan);
    }

    public function endTableEntry() {
        $this->current["align"] = "";
        $newOffset = $this->vOffset + $this->currentFontSize + self::LINE_SPACING;
        if ($newOffset + $this->PAGE_HEIGHT * count($this->current["pages"]) > $this->current["row"]["vPosition"]) {
            $this->current["row"]["vPosition"] = $newOffset + $this->PAGE_HEIGHT * count($this->current["pages"]);
        }
        if (count($this->current["pages"]) > count($this->current["row"]["pages"])) {
            $this->current["row"]["pages"] = $this->current["pages"];
        }
    }

    public function endTableRow() {
        $vOffset = $this->current["vOffset"];
        while($this->isNextPage())
            $this->nextPage();
        // Vertical lines
        for ($i = 0, $x = self::HMARGIN + $this->current["leftSpacing"]; $i <= $this->current["row"]["cellCount"]; $i++, $x += $this->current["row"]["hSize"]) {

            // Don't trace vertical line if colspan
            if (($cellCount = array_shift($this->current["row"]["cutPolicy"])) > 1) {
                array_unshift($this->current["row"]["cutPolicy"], $cellCount - 1);
                continue;
            }

            foreach ($this->current["row"]["pages"] as $page) {
                $page->setRGBStroke(0, 0, 0);
                $page->moveTo($x, self::VMARGIN);
                $page->lineTo($x, $this->PAGE_HEIGHT - self::VMARGIN - $this->current["vOffset"]);
                $page->stroke();
                $this->current["vOffset"] = 0;
            }

            $this->currentPage->moveTo($x, $this->PAGE_HEIGHT - self::VMARGIN - ($this->current["vOffset"]));
            $this->currentPage->lineTo($x, $this->PAGE_HEIGHT - self::VMARGIN - ($this->current["row"]["vPosition"] % $this->PAGE_HEIGHT));
            $this->currentPage->stroke();
            $this->current["vOffset"] = $vOffset;
        }
        // Horizontal line
        $this->currentPage->moveTo(self::HMARGIN + $this->current["leftSpacing"], $this->PAGE_HEIGHT - self::VMARGIN - ($this->current["row"]["vPosition"] % $this->PAGE_HEIGHT));
        $this->currentPage->lineTo($this->PAGE_WIDTH - self::HMARGIN - $this->current["rightSpacing"],
            $this->PAGE_HEIGHT - self::VMARGIN - ($this->current["row"]["vPosition"] % $this->PAGE_HEIGHT));
        $this->currentPage->stroke();

        // Store position
        $this->vOffset = $this->current["row"]["vPosition"] % $this->PAGE_HEIGHT;

        // Store pages
        $last = array_pop($this->old);
        $last["pages"] = array_merge($last["pages"], $this->current["row"]["pages"]);
        array_push($this->old, $last);

        // Erase current properties
        $this->current["row"] = array();
    }

    private function endsWith($str, $sub) {
        return ( substr( $str, strlen( $str ) - strlen( $sub ) ) === $sub );
    }

    private function addImage($url) {
        $image = null;
        if ($this->endsWith(strtolower($url), ".png")) {
            $image = $this->haruDoc->loadPNG($url);
        } elseif ($this->endsWith(strtolower($url), ".jpg") || $this->endsWith(strtolower($url), ".jpeg")) {
            $image = $this->haruDoc->loadJPEG($url);
        }
        if ($image) {
            if ($this->PAGE_HEIGHT - $this->vOffset - 2*self::VMARGIN < $image->getHeight())
                $this->nextPage();
            $this->currentPage->drawImage($image,
                self::HMARGIN + $this->permanentLeftSpacing + $this->hOffset,
                $this->PAGE_HEIGHT - self::HMARGIN - $this->vOffset - $image->getHeight(),
                $image->getWidth(),
                $image->getHeight());

            $this->hOffset = 0;
            $this->vOffset += $image->getWidth();
        }
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/

