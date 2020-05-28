<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\Tests\TestTrait;

use RichCongress\Bundle\UnitBundle\TestCase\TestCase;

/**
 * Class SubSetAssertionTraitTest
 *
 * @package   RichCongress\Bundle\UnitBundle\Tests\TestTrait
 * @author    Matthias Devlamynck <mdevlamynckngress.com>
 * @copyright 2014 - 2020 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\Bundle\UnitBundle\TestTrait\SubSetAssertionTrait
 */
class SubSetAssertionTraitTest extends TestCase
{
    /**
     * @return void
     */
    public function testAssertSubSetShouldPass(): void
    {
        $tested = [1, 2, 42 => 3, 4, 'some key' => 5];
        $expected = [3, 2, 5];

        self::assertSubSet($expected, $tested);
    }

    /**
     * @return void
     */
    public function testAssertSubSetShouldFail(): void
    {
        $tested = [1, 2, 3, 4];
        $expected = [3, 2, 5];

        self::expectException(\PHPUnit\Framework\ExpectationFailedException::class);
        self::assertSubSet($expected, $tested);
    }
}
