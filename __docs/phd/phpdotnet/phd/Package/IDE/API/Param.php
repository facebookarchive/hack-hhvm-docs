<?php
/**
 * Class to parse the function parameters.
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
 * Class to parse the function parameters.
 *
 * @category PhD
 * @package  PhD_IDE
 * @author   Moacir de Oliveira <moacir@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD Style
 * @link     https://doc.php.net/phd/
 */
class Package_IDE_API_Param
{
    /**
     * Parameter name.
     *
     * @var string
     */
    private $name;

    /**
     * Parameter type.
     *
     * @var string
     */
    private $type;

    /**
     * Informs if the parameter is optional or not.
     *
     * @var bool
     */
    private $optional;

    /**
     * Value of the initializer of the parameter.
     *
     * @var string
     */
    private $initializer;

    /**
     * Detailed description of the parameter.
     *
     * @var string
     */
    private $description;

    /**
     * Creates a new instance.
     *
     * @param SimpleXMLElement $xmlElement
     */
    public function __construct(\SimpleXMLElement $xmlElement)
    {
        $this->name         = $xmlElement->name;
        $this->type         = $xmlElement->type;
        $this->optional     = $xmlElement->optional == 'true';
        $this->initializer  = $xmlElement->initializer;
        $this->description  = $xmlElement->description;
    }

    /**
     * Returns a string with the signature of the parameter, with the
     * parameter name, type and initializer.
     *
     * @return string The parameter signature
     */
    public function __toString()
    {
        $str = $this->getType();
        $str .= (substr($this->getName(), 0, 1) != '$') ? ' $' : ' ';
        $str .= $this->getName();

        if ($this->isOptional()) {
            $str .= ' = ';
            if ($this->getInitializer() == '') {
                $str .= "''";
            } else {
                $str .= $this->getInitializer();
            }
        }
        return $str;
    }

    /**
     * Gets the parameter name.
     *
     * @return string Parameter name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the parameter type.
     *
     * @return string Parameter type.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Informs if the parameter is optional or not.
     *
     * @return bool TRUE if the parameter is optional, FALSE otherwise.
     */
    public function isOptional()
    {
        return $this->optional;
    }

    /**
     * Gets the parameter initializer.
     *
     * @return string Value of the parameter initializer.
     */
    public function getInitializer()
    {
        return $this->initializer;
    }

    /**
     * Gets the parameter description.
     *
     * @return string Value of the parameter description.
     */
    public function getDescription()
    {
        return $this->description;
    }

}

/*
 * vim600: sw=4 ts=4 syntax=php et
 * vim<600: sw=4 ts=4
 */
