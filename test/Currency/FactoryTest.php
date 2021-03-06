<?php
/**
 * Currency
 
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace chippyash\Test\Currency;

use chippyash\Currency\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $locale;

    /**
     * Tests expect to run in known locale
     */
    protected function SetUp()
    {
        $this->locale = locale_get_default();
    }

    /**
     * Reinstate system locale
     */
    protected function tearDown()
    {
        locale_set_default($this->locale);
    }

    public function testCreateWillReturnACurrency()
    {
        Factory::setLocale('en_GB');
        $crcy = Factory::create('GBP');
        $this->assertInstanceOf('chippyash\Currency\Currency', $crcy);
        $this->assertEquals('£0.00', $crcy->display());
    }

    public function testCreateCanReturnACurrencyWithAnInitialValue()
    {
        Factory::setLocale('en_GB');
        $crcy = Factory::create('INR',2000.12);
        $this->assertInstanceOf('chippyash\Currency\Currency', $crcy);
        $this->assertEquals('₹2,000.12', $crcy->display());
    }

    public function testCreateWillReturnCurrencyWithCodeIfNoSymbolAvailable()
    {
        Factory::setLocale('en_GB');
        $crcy = Factory::create('XUA',2000);
        $this->assertInstanceOf('chippyash\Currency\Currency', $crcy);
        $this->assertEquals('XUA2,000', $crcy->display());
    }

    public function testCreateWillReturnCurrencyRespectingExponentsForDisplay()
    {
        Factory::setLocale('en_GB');
        $crcy = Factory::create('OMR',2000);
        $this->assertInstanceOf('chippyash\Currency\Currency', $crcy);
        $this->assertEquals('﷼2,000.000', $crcy->display());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Unknown currency:
     */
    public function testCreateWillThrowExceptionForUnknownCurrency()
    {
        $crcy = Factory::create('FOO');
    }

    /**
     * @runInSeparateProcess
     */
    public function testYouCanSetALocaleForCreatingCurrencies()
    {
        Factory::setLocale('fr_FR');
        $crcy = Factory::create('EUR',2000);
        $this->assertInstanceOf('chippyash\Currency\Currency', $crcy);
        $this->assertEquals('2 000,00 €', $crcy->display());
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreateWillDefaultToCurrentDefaultLocaleIfNotSet()
    {
        $this->assertEquals(locale_get_default(), (string) Factory::getLocale());
    }

    /**
     * @runInSeparateProcess
     * @see http://en.wikipedia.org/wiki/Rombo_language
     * @see http://en.wikipedia.org/wiki/Tanzania
     */
    public function testCreateWillUseEnglishNameIfLanguageSpecificNameCannotBeFound()
    {
        Factory::setLocale('rof_TZ');
        $crcy = Factory::create('AFN',2000);
        $this->assertEquals('؋2,000.00', $crcy->display());
        $this->assertEquals('Afghani', $crcy->getName()->get());
    }

    /**
     * @runInSeparateProcess
     * @see http://en.wikipedia.org/wiki/Rombo_language
     */
    public function testCreateWillUseFullLocaleIfLocaleSpecificNameCanBeFound()
    {
        Factory::setLocale('rof');
        $crcy = Factory::create('EUR',2000);
        $this->assertEquals('€2,000.00', $crcy->display());
        $this->assertEquals('yuro', $crcy->getName()->get());
    }

}
