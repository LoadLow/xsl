<?php
namespace Genkgo\Xsl\Xsl\Functions\DateComponent;

use DateTimeInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\ComponentInterface;
use Genkgo\Xsl\Xsl\Functions\Formatter\PictureString;

/**
 * Class MonthComponent
 * @package Genkgo\Xsl\Xsl\Functions\DateComponent
 */
final class MonthComponent implements ComponentInterface {

    /**
     * @param PictureString $pictureString
     * @param DateTimeInterface $date
     * @return string
     */
    public function format(PictureString $pictureString, DateTimeInterface $date)
    {
        return 'm';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'M';
    }
}