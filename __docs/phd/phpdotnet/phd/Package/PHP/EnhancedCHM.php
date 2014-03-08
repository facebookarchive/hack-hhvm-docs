<?php
namespace phpdotnet\phd;
/*  $Id$ */

class Package_PHP_EnhancedCHM extends Package_PHP_CHM
{
    // Do not bother processing notes if we haven't got them this time.
    protected $haveNotes = false;

    // Where are the usernotes?
    protected $userNotesBaseDir = null;

    public function __construct() {
        parent::__construct();
        $this->registerFormatName("PHP-EnhancedCHM");
    }

    public function update($event, $val = null) {
        switch($event) {
        case Render::INIT:
            parent::update($event, $val);

            // Use %TEMP%/usernotes as base directory for Usernotes.
            $temp = sys_get_temp_dir();
            if (!$temp || !is_dir($temp)) {
                v('Unable to locate the systems temporary system directory for EnhancedCHM.', E_USER_ERROR);
                break;
            }

            $this->userNotesBaseDir = $temp . DIRECTORY_SEPARATOR . 'usernotes' . DIRECTORY_SEPARATOR;

            // Make the usernotes directory.
            if(!file_exists($this->userNotesBaseDir) || is_file($this->userNotesBaseDir)) {
                mkdir($this->userNotesBaseDir, 0777, true) or v("Can't create the usernotes directory : %s", $this->userNotesBaseDir, E_USER_ERROR);
            }

            // Get the local last-updated value.
            $userNotesLastUpdatedLocal = file_exists($this->userNotesBaseDir . 'last-updated') ? intval(file_get_contents($this->userNotesBaseDir . 'last-updated')) : 0;

            // Get the remote last-updated value to see if we need to do anything with the usernotes we already have.
            v('Checking usernotes.', VERBOSE_MESSAGES);
            $userNotesLastUpdatedRemote = intval(file_get_contents('http://www.php.net/backend/notes/last-updated'));

            // Compare the remote and local last-updated values.
            if ($userNotesLastUpdatedLocal < $userNotesLastUpdatedRemote) {

                // Make sure the bz2 extension is loaded.
                // Whilst this PhD format is going to be used on Windows, make it non windows compliant by using the appropriate filenaming conventions.
                if (!extension_loaded('bz2')) {
                    dl((PHP_SHLIB_SUFFIX === 'dll' ? 'php_' : '') . 'bz2.' . PHP_SHLIB_SUFFIX);
                }

                if (!extension_loaded('bz2')) {
                    v('The BZip2 extension is not available.', E_USER_ERROR);
                    break;
                }

                // Remove any existing files.
                foreach(glob($this->userNotesBaseDir . '*' . DIRECTORY_SEPARATOR . '*') as $sectionFile) {
                    unlink($sectionFile);
                }

                // Use a decompression stream filter to save having to store anything locally other than the expanded user notes.
                if (false === ($fpNotes = fopen('http://www.php.net/backend/notes/all.bz2', 'rb'))) {
                    v('Failed to access the usernotes archive.', E_USER_ERROR);
                    break;
                }

                $fpsfNotes = stream_filter_append($fpNotes, 'bzip2.decompress', STREAM_FILTER_READ, array('small' => true));

                // Extract the usernotes and store them by page and date.
                v('Preparing usernotes.', VERBOSE_MESSAGES);

                // Decompress the 'all' file into single files - one file per sectionid.
                while($fpNotes && !feof($fpNotes) && false !== ($userNote = fgetcsv($fpNotes, 0, '|'))) {
                    // Usernote index
                    // 0 = Note ID
                    // 1 = Section ID
                    // 2 = Rate
                    // 3 = Timestamp
                    // 4 = User
                    // 5 = Note

                    $sectionHash = md5($userNote[1]);
                    $sectionDir = $this->userNotesBaseDir . $sectionHash[0];

                    if (!file_exists($sectionDir)) {
                        mkdir($sectionDir, 0777, true);
                    }

                    file_put_contents($sectionDir . DIRECTORY_SEPARATOR . $sectionHash, implode('|', $userNote) . PHP_EOL, FILE_APPEND);
                }

                stream_filter_remove($fpsfNotes);
                fclose($fpNotes);

                // Save the last-updated data.
                file_put_contents($this->userNotesBaseDir . 'last-updated', $userNotesLastUpdatedRemote);

                $this->haveNotes = true;
                v('Usernotes prepared.', VERBOSE_MESSAGES);
            } else {
                v('Usernotes not updated.', VERBOSE_MESSAGES);
            }

            break;

        default:
            parent::update($event, $val);
        }
    }

    public function footer($id) {
        $footer = parent::footer($id);

        // Find usernotes file.
        $idHash = md5($id);
        $userNotesFile = $this->userNotesBaseDir . $idHash[0] . DIRECTORY_SEPARATOR . $idHash;

        if (!file_exists($userNotesFile)) {
            $notes = ' <div class="note">There are no user contributed notes for this page.</div>';
        } else {
            $notes = '';

            foreach(file($userNotesFile) as $userNote) {
                // Usernote index
                // 0 = Note ID
                // 1 = Section ID
                // 2 = Rate
                // 3 = Timestamp
                // 4 = User
                // 5 = Note
                list($noteId, $noteSection, $noteRate, $noteTimestamp, $noteUser, $noteText) = explode('|', $userNote);

                if ($noteUser) {
                    $noteUser = '<strong class="user">' . htmlspecialchars($noteUser) . '</strong>';
                }
                $noteDate = '<a href="#' . $noteId . '" class="date">' . date("d-M-Y h:i", $noteTimestamp) . '</a>';
                $anchor   = '<a name="' . $noteId . '""></a>';

                $noteText = str_replace(
                    array(
                        '&nbsp;',
                        '<br />',
                        // Use classes rather than colors.
                        '<span style="color: ' . ini_get('highlight.comment'),
                        '<span style="color: ' . ini_get('highlight.default'),
                        '<span style="color: ' . ini_get('highlight.keyword'),
                        '<span style="color: ' . ini_get('highlight.string'),
                        '<span style="color: ' . ini_get('highlight.html'),
                        '</font>',
                        "\n ",
                        '  ',
                        '  '
                    ),
                    array(
                        ' ',
                        "<br />\n",
                        '<span class="comment',
                        '<span class="default',
                        '<span class="keyword',
                        '<span class="string',
                        '<span class="html',
                        '</span>',
                        "\n&nbsp;",
                        '&nbsp; ',
                        '&nbsp; '
                    ),
                    preg_replace(
                        '!((mailto:|(http|ftp|nntp|news):\/\/).*?)(\s|<|\)|"|\\\\|\'|$)!',
                        '<a href="\1" rel="nofollow" target="_blank">\1</a>\4',
                        highlight_string(trim(base64_decode($noteText)), true))
                    );

                $notes .= <<< END_NOTE
  {$anchor}
  <div class="note">
   {$noteUser}
   {$noteDate}
   <div class="text">
    <div class="phpcode">
{$noteText}
    </div>
   </div>
  </div>
 <div class="foot"></div>

END_NOTE;
            }

            $notes = '<div id="allnotes">' . $notes . '</div>';
        }

        return <<< USER_NOTES
<div id="usernotes">
 <div class="head">
  <h3 class="title">User Contributed Notes</h3>
 </div>
{$notes}
</div>
{$footer}
USER_NOTES;

    }
}
