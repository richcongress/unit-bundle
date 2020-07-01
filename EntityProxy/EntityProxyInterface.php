<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\EntityProxy;

/**
 * Interface EntityProxyInterface
 *
 * @package   RichCongress\Bundle\UnitBundle\EntityProxy
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 */
interface EntityProxyInterface
{
    /**
     * @return string
     */
    public static function getEntityClass(): string;

    /**
     * @return object
     */
    public static function makeDefault();
}
