<?php
namespace phpdotnet\phd;
/* $Id$ */

class Package_IDE_PHPStub extends Package_IDE_Base {

    public function __construct() {
        $this->registerFormatName('IDE-PHPStub');
        $this->setExt(Config::ext() === null ? ".php" : Config::ext());
    }

    public function parseFunction() {
        $str = <<< EOD
{$this->renderFunctionDocBlock()}
{$this->renderFunctionDefinition()}
EOD;
        return $str;
    }

    private function renderFunctionDefinition() {
        return "function {$this->function['name']}({$this->renderParamBody()}) {}";
    }

    private function renderFunctionDocBlock() {
        $valid_parts = array_filter(array(
            '/**',
            $this->renderFunctionVersion(),
            $this->renderFunctionPurpose(),
            $this->renderFunctionLink(),
            $this->renderParamDocs(),
            $this->renderReturnDocBlock()
        ));

        return implode("\n", $valid_parts) . "\n */";
    }

    private function renderFunctionVersion() {
        if (empty($this->function['version'])) {
            return "";
        }

        return " * ({$this->function['version']})<br/>";
    }

    private function renderFunctionPurpose() {
        if (empty($this->function['purpose'])) {
            return "";
        }

        return " * {$this->function['purpose']}";
    }

    private function renderFunctionLink() {
        return " * @link http://php.net/manual/en/{$this->function['manualid']}.php";
    }

    private function renderReturnDocBlock() {
        $description = preg_replace('/\n\s+/', "\n * ", $this->function['return']['description']);
        return " * @return {$this->function['return']['type']} $description";
    }

    private function renderParamDocs() {
        $result = array();
        foreach($this->function['params'] as $param) {
            $optional = $param['optional'] ? '[optional]' : '';
            $result[] = " * @param {$param['type']} \${$param['name']} $optional <p> TODO DESCRIPTION </p>";
        }

        return implode("\n", $result);
    }

    private function renderParamBody() {
        $result = array();
        foreach($this->function['params'] as $param) {
            if ($param['optional'] && isset($param['initializer'])) {
                $result[] = "\${$param['name']} = {$param['initializer']}";
            } else {
                $result[] = "\${$param['name']}";
            }
        }

        return implode(", ", $result);
    }
}

/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
