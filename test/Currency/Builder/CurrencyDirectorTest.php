<?php
/**
 * Currency
 
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace chippyash\Test\Currency\Builder;


use chippyash\Type\String\StringType;
use chippyash\Currency\Builder\CurrencyDirector;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;

class CurrencyDirectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CurrencyDirector
     */
    protected $sut;

    /**
     * @var vfsStreamFile
     */
    protected $rootDir;

    /**
     * @var string
     */
    protected $saveLocale;

    protected function setUp()
    {
        $this->saveLocale = locale_get_default();
        locale_set_default('en_GB');
        $this->rootDir = vfsStream::setup('foo');
        $this->sut = new CurrencyDirector(new StringType('GBP'), new StringType('foo\bar'), new StringType($this->rootDir->url()));
    }

    protected function tearDown()
    {
        locale_set_default($this->saveLocale);
    }

    public function testBuildWillSaveCurrencyClassFileAndReturnFileContents()
    {
        $text = $this->sut->build();
        $this->assertFileExists($this->rootDir->url() . '/GBP.php');
        $this->assertEquals($text, file_get_contents($this->rootDir->url() . '/GBP.php'));
    }

}
