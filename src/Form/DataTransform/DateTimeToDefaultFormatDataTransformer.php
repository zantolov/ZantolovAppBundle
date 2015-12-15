<?php

namespace Zantolov\AppBundle\Form\DataTransform;

use Symfony\Component\Form\DataTransformerInterface;

class DateTimeToDefaultFormatDataTransformer implements DataTransformerInterface
{
    private $format;

    /**
     * DateTimeToDefaultFormatDataTransformer constructor.
     * @param $format
     */
    public function __construct($format)
    {
        $this->format = $format;
    }


    /**
     * @param mixed $datetime
     * @return bool|string
     */
    public function transform($datetime)
    {
        if ($datetime instanceof \DateTime) {
            return $datetime->format($this->format);
        }

        if (is_string($datetime)) {
            return date($this->format, strtotime($datetime));
        }
    }

    /**
     * @param mixed $number
     * @return null|object
     */
    public function reverseTransform($dateTime)
    {
        if (empty($dateTime)) {
            return null;
        }
        $dateTime = \DateTime::createFromFormat($this->format, $dateTime);
        return $dateTime;
    }
}