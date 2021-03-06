<?php
declare(strict_types=1);
/**
 * /tests/Integration/Doctrine/DBAL/Types/UTCDateTimeTypeTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests\Integration\Doctrine\DBAL\Types;

use App\Doctrine\DBAL\Types\UTCDateTimeType;
use App\Utils\Tests\PhpUnitUtil;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UTCDateTimeTypeTest
 *
 * @package App\Tests\Integration\Doctrine\DBAL\Types
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class UTCDateTimeTypeTest extends KernelTestCase
{
    /**
     * @var AbstractPlatform
     */
    private $platform;

    /**
     * @var \Doctrine\DBAL\Types\Type
     */
    private $type;

    public function testThatDateTimeConvertsToDatabaseValue(): void
    {
        $dateInput = new \DateTime('1981-04-07 10:00:00', new \DateTimeZone('Europe/Helsinki'));
        $dateExpected = clone $dateInput;

        $expected = $dateExpected
            ->setTimezone(new \DateTimeZone('UTC'))
            ->format($this->platform->getDateTimeTzFormatString());

        $actual = $this->type->convertToDatabaseValue($dateInput, $this->platform);

        static::assertSame($expected, $actual);

        unset($actual, $expected, $dateExpected, $dateInput);
    }

    /**
     * @throws \ReflectionException
     */
    public function testThatConvertToDatabaseValueCreatesTimeZoneInstanceIfItIsNull(): void
    {
        PhpUnitUtil::setProperty('utc', null, $this->type);

        static::assertNull(PhpUnitUtil::getProperty('utc', $this->type));

        $dateInput = new \DateTime('1981-04-07 10:00:00', new \DateTimeZone('Europe/Helsinki'));

        $this->type->convertToDatabaseValue($dateInput, $this->platform);

        /** @var \DateTimeZone $property */
        $property = PhpUnitUtil::getProperty('utc', $this->type);

        static::assertInstanceOf(\DateTimeZone::class, $property);
        static::assertSame('UTC', $property->getName());

        unset($dateInput);
    }

    /**
     * @dataProvider dataProviderTestDateTimeConvertsToPHPValue
     *
     * @param string           $expected
     * @param string|\DateTime $value
     */
    public function testDateTimeConvertsToPHPValue(string $expected, $value): void
    {
        $date = $this->type->convertToPHPValue($value, $this->platform);

        $this->assertInstanceOf('DateTime', $date);
        $this->assertEquals($expected, $date->format('Y-m-d H:i:s'));
    }

    /**
     * @return array
     */
    public function dataProviderTestDateTimeConvertsToPHPValue(): array
    {
        return [
            [
                '1981-04-07 10:00:00',
                '1981-04-07 10:00:00',
            ],
            [
                '1981-04-07 07:00:00',
                new \DateTime('1981-04-07 10:00:00', new \DateTimeZone('Europe/Helsinki')),
            ],
            [
                '1981-04-07 10:00:00',
                new \DateTime('1981-04-07 10:00:00', new \DateTimeZone('UTC')),
            ],
        ];
    }

    /**
     * @throws \ReflectionException
     */
    public function testThatConvertToPHPValueCreatesTimeZoneInstanceIfItIsNull(): void
    {
        PhpUnitUtil::setProperty('utc', null, $this->type);

        static::assertNull(PhpUnitUtil::getProperty('utc', $this->type));

        $this->type->convertToPHPValue('1981-04-07 10:00:00', $this->platform);

        /** @var \DateTimeZone $property */
        $property = PhpUnitUtil::getProperty('utc', $this->type);

        static::assertInstanceOf(\DateTimeZone::class, $property);
        static::assertSame('UTC', $property->getName());
    }

    /**
     * @expectedException \Doctrine\DBAL\Types\ConversionException
     */
    public function testThatConvertToPHPValueThrowsAnExceptionWithInvalidValue(): void
    {
        $this->type->convertToPHPValue('foobar', $this->platform);
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->platform = new MySqlPlatform();

        if (Type::hasType('datetime')) {
            Type::overrideType('datetime', UTCDateTimeType::class);
        } else {
            Type::addType('datetime', UTCDateTimeType::class);
        }

        $this->type = Type::getType('datetime');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->type, $this->platform);
    }
}
