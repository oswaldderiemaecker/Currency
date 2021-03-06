<?php
/**
 * Currency
 
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace chippyash\Currency\Builder;


use chippyash\BuilderPattern\AbstractDirector;
use chippyash\Type\String\StringType;

/**
 * Currency class build director
 */
class CurrencyDirector extends AbstractDirector
{
    /**
     * @param StringType $crcyCode Currency code to build
     * @param StringType $namespace Namespace to implement class in
     * @param StringType $outDir Directory to output class code file to
     */
    public function __construct(StringType $crcyCode, StringType $namespace, StringType $outDir)
    {
        $builder = new CurrencyBuilder($crcyCode);
        $renderer = new CurrencyRenderer($namespace, $outDir);
        parent::__construct($builder, $renderer);
    }
}