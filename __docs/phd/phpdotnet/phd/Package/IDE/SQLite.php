<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_IDE_SQLite extends Package_IDE_Base {

    private $sql = array();
    private $db = null;

    public function __construct() {
        $this->registerFormatName('IDE-SQLite');
        $this->setExt(Config::ext() === null ? ".sqlite" : Config::ext());
    }

    public function INIT($value) {
        $this->loadVersionInfo();
        $this->createDatabase();
    }

    public function FINALIZE($value) {
         $this->db->exec('BEGIN TRANSACTION; ' . join(' ', $this->sql) . ' COMMIT');
         if ($this->db->lastErrorCode()) {
             trigger_error($this->db->lastErrorMsg(), E_USER_WARNING);
         }
    }

    public function writeChunk () {
        if (!isset($this->cchunk['funcname'][0])) {
             return;
        }
        if (false !== strpos($this->cchunk['funcname'][0], ' ')) {
            return;
        }

        $this->function['version'] = $this->versionInfo($this->cchunk['funcname'][0]);

        foreach ($this->cchunk['funcname'] as $funcname) {
            $this->function['name'] = $funcname;
            $this->parseFunction();
        }
    }

    public function parseFunction() {
        $sql = sprintf("INSERT INTO functions (name, purpose, manual_id, version, return_type, return_description, errors) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s');",
            $this->db->escapeString($this->function['name']),
            $this->db->escapeString($this->function['purpose']),
            $this->db->escapeString($this->function['manualid']),
            $this->db->escapeString($this->function['version']),
            $this->db->escapeString($this->function['return']['type']),
            $this->db->escapeString($this->function['return']['description']),
            $this->db->escapeString($this->function['errors'])
        );

        foreach ((array)$this->function['params'] as $param) {
            $sql .= sprintf("INSERT INTO params (function_name, name, type, description, optional, initializer) VALUES ('%s', '%s', '%s', '%s', '%s', '%s');",
                $this->db->escapeString($this->function['name']),
                $this->db->escapeString($param['name']),
                $this->db->escapeString($param['type']),
                $this->db->escapeString(isset($param['description']) ? $param['description'] : ''),
                $this->db->escapeString($param['optional'] === 'true' ? 1 : 0),
                $this->db->escapeString(isset($param['initializer']) ? $param['initializer'] : '')
             );
        }

        foreach ((array)$this->function['notes'] as $entry) {
            $sql .= sprintf("INSERT INTO notes (function_name, type, description) VALUES ('%s', '%s', '%s');",
                $this->db->escapeString($this->function['name']),
                $this->db->escapeString($entry['type']),
                $this->db->escapeString($entry['description'])
             );
        }

        foreach ((array)$this->function['changelog'] as $entry) {
            $sql .= sprintf("INSERT INTO changelogs (function_name, version, change) VALUES ('%s', '%s', '%s');",
                $this->db->escapeString($this->function['name']),
                $this->db->escapeString($entry['version']),
                $this->db->escapeString($entry['change'])
             );
        }

        foreach ((array)$this->function['seealso'] as $entry) {
            $sql .= sprintf("INSERT INTO seealso (function_name, name, type) VALUES ('%s', '%s', '%s');",
                $this->db->escapeString($this->function['name']),
                $this->db->escapeString($entry['name']),
                $this->db->escapeString($entry['type'])
             );
        }

        $this->sql[] = $sql;
    }

    public function createDatabase() {
        $db = new \SQLite3(Config::output_dir() . strtolower($this->getFormatName()) . $this->getExt());
        $db->exec('DROP TABLE IF EXISTS functions');
        $db->exec('DROP TABLE IF EXISTS params');
        $db->exec('DROP TABLE IF EXISTS notes');
        $db->exec('DROP TABLE IF EXISTS seealso');
        $db->exec('DROP TABLE IF EXISTS changelogs');
        $db->exec('PRAGMA default_synchronous=OFF');
        $db->exec('PRAGMA count_changes=OFF');
        $db->exec('PRAGMA cache_size=100000');
        $db->exec($this->createSQL());

        $this->db = $db;
    }

    public function createSQL() {
        return <<<SQL
CREATE TABLE functions (
    name TEXT,
    purpose TEXT,
    manual_id TEXT,
    version TEXT,
    return_type TEXT,
    return_description TEXT,
    errors TEXT
);

CREATE TABLE params (
    function_name TEXT,
    name TEXT,
    type TEXT,
    description TEXT,
    optional INTEGER,
    initializer TEXT
);

CREATE TABLE notes (
    function_name TEXT,
    type TEXT,
    description TEXT
);

CREATE TABLE seealso (
    function_name TEXT,
    name TEXT,
    type TEXT,
    description TEXT
);

CREATE TABLE changelogs (
    function_name TEXT,
    version TEXT,
    change TEXT
);

SQL;
    }

}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
