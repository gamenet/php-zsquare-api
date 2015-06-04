<?php
namespace ZSquare;

/**
 * Class Product
 *
 * @package ZSquare
 * @author Nikolay Bondarenko <misterionkell@gmail.com>
 * @version 1.0
 * @license The MIT License (MIT)
 */
class Product
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $label;

    public function __construct($value, $label)
    {
        $this->value = $value;
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}