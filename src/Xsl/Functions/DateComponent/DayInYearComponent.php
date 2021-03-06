<?php
namespace Genkgo\Xsl\Xsl\Functions\DateComponent;

use DateTimeInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\ComponentInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

/**
 * Class DayInYearComponent
 * @package Genkgo\Xsl\Xsl\Functions\DateComponent
 */
final class DayInYearComponent implements ComponentInterface {

    /**
     * @param PictureString $pictureString
     * @param DateTimeInterface $date
     * @return string
     */
    public function format(PictureString $pictureString, DateTimeInterface $date)
    {
        return (string)((int)$date->format('z') + 1);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'd';
    }
}