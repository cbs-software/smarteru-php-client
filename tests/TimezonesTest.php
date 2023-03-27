<?php
/*
 * This file contains the Tests\CBS\SmarterU\TimezonesTest.
 *
 * @author Brian Reich <brian.reich@thecoresolution.com>
 * @copyright $year$ Core Business Solutions
 * @license Proprietary
 * @since 2023/03/27
 * @version $version$
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU;

use CBS\SmarterU\Timezones;
use PHPUnit\Framework\TestCase;

/**
 * Tests Timezones
 */
class TimezonesTest extends TestCase {

    #region Tests that are possibly too much

    /**
     * Verifies that getProvidedNameFromDisplayValue() returns the right value
     * when a valid display value is passed.
     *
     * @dataProvider displayValueProvider
     */
    public function testGetProvidedNameFromDisplayValueReturnsMatchingProvidedNameForValidDisplayValue(string $displayValue) {
        $flippedTimezones = array_flip(Timezones::VALID_TIMEZONES_PROVIDED_NAME_TO_DISPLAY_VALUE);

        $this->assertEquals(
            $flippedTimezones[$displayValue],
            Timezones::getProvidedNameFromDisplayValue($displayValue)
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
            Timezones::VALID_TIMEZONES_PROVIDED_NAME_TO_DISPLAY_VALUE[$providedName],
            Timezones::getDisplayValueFromProvidedName($providedName)
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
            array_keys(array_flip(Timezones::VALID_TIMEZONES_PROVIDED_NAME_TO_DISPLAY_VALUE))
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
            array_keys(Timezones::VALID_TIMEZONES_PROVIDED_NAME_TO_DISPLAY_VALUE)
        );
    }

    #endregion Tests that are possibly too much

    /**
     * Verifies that getProvidedNameFromDisplayValue throws an exception when
     * an invalid display value is passed.
     */
    public function testGetProvidedNameFromDisplayValueThrowsExceptionForInvalidDisplayValue() {
        $this->expectException(\InvalidArgumentException::class);
        Timezones::getProvidedNameFromDisplayValue('invalid');
    }

    /**
     * Verifies that getDisplayValueFromProvidedName throws an exception when
     * an invalid provided name is passed.
     */
    public function testGetDisplayValueFromProvidedNameThrowsExceptionForInvalidProvidedName() {
        $this->expectException(\InvalidArgumentException::class);
        Timezones::getDisplayValueFromProvidedName('invalid');
    }
}
