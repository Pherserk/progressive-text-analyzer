<?php

namespace Pherserk\ProgressiveTextAnalyzer\test\component;

use Pherserk\Language\model\LanguageInterface;
use Pherserk\ProgressiveTextAnalyzer\component\ProgressiveTextAnalyzer;
use Pherserk\SignExtractor\component\SignExtractor;
use Pherserk\SignProvider\component\SignProviderInterface;
use PHPUnit\Framework\TestCase;

class ProgressiveTextAnalyzerTest extends TestCase
{

    /**
     * @dataProvider provideLanguageAndTextAndResults
     */
    public function testGetSignAnalysis(string $languageCode, string $languageName, string $languageNativeName, string $text, array $results)
    {
	$signProvider = $this->prophesize(SingProviderInterface::class);
	
        $analyzer = new ProgressiveTextAnalyzer($signProvider->reveal());

        $language = $this->prophesize(LanguageInterface::class);

        $results = $analyzer->getSignAnalysis($language->reveal(), $text);        
    }

    public function provideLanguageAndTextAndResults()
    {    
        return [
            [
                'english',
                'english',
                'en',
                'This is a test',
                [],               
            ],
        ];
    }
}

