<?php

namespace Pherserk\ProgressiveTextAnalyzer\test\component;

use Pherserk\ProgressiveTextAnalyzer\component\ProgressiveTextAnalyzer;
use PHPUnit\Framework\TestCase;

class ProgressiveTextAnalyzerTest extends TestCase
{

    /**
     * @dataProvider provideLanguageAndTextAndResults
     */
    public function testGetSignAnalysis(string $text) 
    {
        $analyzer = new ProgressiveTextAnalyzer();
        $results = $analyzer->getSignAnalysis($text);        
    }

    public function provideLanguageAndTextAndResults()
    {    
        return [
            [
                'This is a test',
            ],
        ];
    }
}

