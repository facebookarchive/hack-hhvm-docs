<?php
/**
 * Class to parse the IDE Function xml.
 *
 * PHP version 5.3+
 *
 * @category PhD
 * @package  PhD_IDE
 * @author   Moacir de Oliveira <moacir@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @version  SVN: $Id$
 * @link     https://doc.php.net/phd/
 */
namespace phpdotnet\phd;

/**
 * Class to parse the IDE Function xml.
 *
 * @category PhD
 * @package  PhD_IDE
 * @author   Moacir de Oliveira <moacir@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @link     https://doc.php.net/phd/
 */
class Package_IDE_API_Function
{
    /**
     * Function name.
     *
     * @var string
     */
    private $name;

    /**
     * Function version.
     * Extracted from the file doc-base/version.xml.
     *
     * @var string
     */
    private $version;

    /**
     * Function purpose.
     * Value of the <refpurpose> of the function refentry.
     *
     * @var string
     */
    private $purpose;

    /**
     * Value of the xml:id of the function in the Manual.
     *
     * @var string
     */
    private $manualid;

    /**
     * Return type of the function.
     *
     * @var string
     */
    private $returnType;

    /**
     * Description of the return value of the function.
     * Content of the <refsect1 role="returnvalues"> of the Manual.
     *
     * @var string
     */
    private $returnDescription;

    /**
     * Array with the function parameters.
     * Array of Package_IDE_API_Param.
     *
     * @var array
     */
    private $params = array();

    /**
     * Array with the see also entries of the function.
     * Indexes:
     *     'name': Function name
     *     'type': Only 'function' is supported at this time
     *     'description': TODO
     *
     * @var array
     */
    private $seeAlsoEntries = array();

    /**
     * Array with the changelog entries of the function.
     * Indexes:
     *     'version': PHP version of change
     *     'change': The change
     *
     * @var array
     */
    private $changeLogEntries = array();

    /**
     * Description of the errors the function may throw.
     * Content of the <refsect1 role="errors"> of the Manual.
     *
     * @var string
     */
    private $errors;

    /**
     * Creates a new instance of Package_IDE_API_Function.
     *
     * @param SimpleXMLElement $xmlElement A new SimpleXMLElement
     *        of the function file.
     */
    public function __construct(\SimpleXMLElement $xmlElement)
    {
        $this->name                 = $xmlElement->name;
        $this->version              = $xmlElement->version;
        $this->purpose              = $xmlElement->purpose;
        $this->manualid             = $xmlElement->manualid;
        $this->returnType           = $xmlElement->return->type;
        $this->returnDescription    = $xmlElement->return->description;
        $this->errors               = $xmlElement->errors->description;

        if (isset($xmlElement->params->param)) {
            foreach ($xmlElement->params->param as $param) {
                $this->params[] = new Package_IDE_API_Param($param);
            }
        }
        if (isset($xmlElement->seealso->entry)) {
            foreach ($xmlElement->seealso->entry as $entry) {
                $entryArray['name'] = $entry->name;
                $entryArray['type'] = $entry->type;
                $entryArray['description'] = $entry->description;
                $this->seeAlsoEntries[] = $entryArray;
            }
        }
        if (isset($xmlElement->changelog->entry)) {
            foreach ($xmlElement->changelog->entry as $entry) {
                $entryArray['version'] = $entry->version;
                $entryArray['change']  = $entry->change;
                $this->changeLogEntries[] = $entryArray;
            }
        }
    }

    /**
     * Returns the signature of the function.
     * Example for the fread() function:
     *     resource fread(resource $handle, int $length)
     */
    public function __toString()
    {
        $str = $this->getReturnType() . ' ' . $this->getName() . '(';
        $p = $opts = 0;
        foreach ($this->getParams() as $param) {
            if ($param->isOptional()) {
                $str .= (($p > 0) ? ' [, ': '[');
                $opts++;
            } else {
                $str .= ($p > 0) ? ', ' : '';
            }
            $str .= $param;
            $p++;
        }
        $str .= str_repeat(']', $opts);
        $str .= ')';
        return $str;
    }

    /**
     * Gets the function name.
     *
     * @return string Function name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the function version.
     * Extracted from the file doc-base/version.xml.
     *
     * @return string Function version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Gets the function purpose.
     * Value of the <refpurpose> of the function refentry.
     *
     * @return string Function purpose
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * Gets the value of the xml:id of the function in the Manual.
     *
     * @return string The xml:id of the function
     */
    public function getManualID()
    {
        return $this->manualid;
    }

    /**
     * Check if the function contains changelog entries.
     *
     * @return bool True if the function has changelog entries
     */
    public function hasChangeLogEntries()
    {
        return sizeof($this->changeLogEntries) > 0;
    }

    /**
     * Check if the function contains seealso entries.
     *
     * @return bool True if the function has seealso entries
     */
    public function hasSeeAlsoEntries()
    {
        return sizeof($this->seeAlsoEntries) > 0;
    }

    /**
     * Check if the function has parameters.
     *
     * @return bool True if the function has parameters
     */
    public function hasParams()
    {
        return sizeof($this->params) > 0;
    }

    /**
     * Gets an array with the function parameters.
     * Array of Package_IDE_API_Param.
     *
     * @return array Array with the function parameters
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Gets the return type of the function.
     *
     * @return string Function return type
     */
    public function getReturnType()
    {
        return $this->returnType;
    }

    /**
     * Gets the description of the return value of the function.
     * Content of the <refsect1 role="returnvalues"> of the Manual.
     *
     * @return string Description of the return value of the function
     */
    public function getReturnDescription()
    {
        return $this->returnDescription;
    }

    /**
     * Array with the see also entries of the function.
     *
     * @return array Array with the see also entries.
     */
    public function getSeeAlsoEntries()
    {
        return $this->seeAlsoEntries;
    }

    /**
     * Array with the changelog entries of the function.
     *
     * @return array Array with the changelog entries.
     */
    public function getChangeLogEntries()
    {
        return $this->changeLogEntries;
    }

    /**
     * Content of the <refsect1 role="errors"> of the function.
     *
     * @return string The description of the errors the function may throw.
     */
    public function getErrors()
    {
        return $this->errors;
    }

}

/*
 * vim600: sw=4 ts=4 syntax=php et
 * vim<600: sw=4 ts=4
 */
