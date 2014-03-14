<?php

namespace Peredaj\JQueryBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class ArrayToStringTransformer implements DataTransformerInterface
{

    private $separator;

    public function __construct($separator = ',')
    {
        $this->separator = $separator;
    }

    public function transform($array)
    {
        if (null === $array || !is_array($array))
        {
            return '';
        }

        return implode($this->separator, $array);
    }

    public function reverseTransform($string)
    {
        if (is_array($string))
        {
            return $string;
        }

        return explode($this->separator, $string);
    }

}