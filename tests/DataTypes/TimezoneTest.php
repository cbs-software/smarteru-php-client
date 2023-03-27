<?php
/*
 * This file contains the Tests\CBS\SmarterU\TimezoneTest.
 *
 * @author Brian Reich <brian.reich@thecoresolution.com>
 * @copyright $year$ Core Business Solutions
 * @license Proprietary
 * @since 2023/03/27
 * @version $version$
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\Timezone;
use PHPUnit\Framework\TestCase;

/**
 * Tests Timezone
 */
class TimezoneTest extends TestCase {

    #region Tests that are possibly too much

    /**
     * Verifies that getProvidedNameFromDisplayValue() returns the right value
     * when a valid display value is passed.
     *
     * @dataProvider displayValueProvider
     */
    public function testGetProvidedNameFromDisplayValueReturnsMatchingProvidedNameForValidDisplayValue(string $displayValue) {
        $flippedTimezones = array_flip(Timezone::VALID_TIMEZONES_PROVIDED_NAME_TO_DISPLAY_VALUE);

        $this->assertEquals(
            $flippedTimezones[$displayValue],
            Timezone::getProvidedNameFromDisplayValue($displayValue)
        );
    }

    /**
     * Verifies that getDisplayValueFromProvidedName returns the right value
     * when a valid provided name is passed.
     *
     * @dataProvider providedNameProvider
     */
    public function testGetDisplayValueFromProvidedNameReturnsMatchingDisplayValueForValidProvidedName(string $providedName) {
        $this->assertEquals(
            Timezone::VALID_TIMEZONES_PROVIDED_NAME_TO_DISPLAY_VALUE[$providedName],
            Timezone::getDisplayValueFromProvidedName($providedName)
        );
    }

    /**
     * Provides a list of valid Display Values.
     *
     * @return array<string> A list of valid Display Values.
     */
    public function displayValueProvider(): array {
        return array_map(
            fn ($current) => [$current],
            array_keys(array_flip(Timezone::VALID_TIMEZONES_PROVIDED_NAME_TO_DISPLAY_VALUE))
        );
    }

    /**
     * Provides a list of valid Provided Names.
     *
     * @return array<string> A list of valid Provided Names.
     */
    public function providedNameProvider(): array {
        return array_map(
            fn ($current) => [$current],
            array_keys(Timezone::VALID_TIMEZONES_PROVIDED_NAME_TO_DISPLAY_VALUE)
        );
    }

    #endregion Tests that are possibly too much

    /**
     * Verifies that getProvidedNameFromDisplayValue throws an exception when
     * an invalid display value is passed.
     */
    public function testGetProvidedNameFromDisplayValueThrowsExceptionForInvalidDisplayValue() {
        $this->expectException(\InvalidArgumentException::class);
        Timezone::getProvidedNameFromDisplayValue('invalid');
    }

    /**
     * Verifies that getDisplayValueFromProvidedName throws an exception when
     * an invalid provided name is passed.
     */
    public function testGetDisplayValueFromProvidedNameThrowsExceptionForInvalidProvidedName() {
        $this->expectException(\InvalidArgumentException::class);
        Timezone::getDisplayValueFromProvidedName('invalid');
    }

    /**
     * Verifies that fromProvidedName() returns a Timezone instance for a valid
     * provided name.
     */
    public function testFromProvidedNameReturnsTimezoneForValidValue() {
        $instance = Timezone::fromProvidedName('US/Eastern');

        $this->assertInstanceOf(Timezone::class, $instance);
        $this->assertEquals('US/Eastern', $instance->getProvidedName());
        $this->assertEquals('(GMT-5:00) - US/Eastern', $instance->getDisplayValue());
    }

    /**
     * Verifies taht fromDisplayValue() returns a Timezone instance for a valid
     * display value.
     */
    public function testFromDisplayValueReturnsTimezoneForValidValue() {
        $instance = Timezone::fromDisplayValue('(GMT-5:00) - US/Eastern');

        $this->assertInstanceOf(Timezone::class, $instance);
        $this->assertEquals('US/Eastern', $instance->getProvidedName());
        $this->assertEquals('(GMT-5:00) - US/Eastern', $instance->getDisplayValue());
    }
}
