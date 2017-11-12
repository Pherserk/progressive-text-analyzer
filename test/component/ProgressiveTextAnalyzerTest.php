<?php

namespace Pherserk\ProgressiveTextAnalyzer\test\component;

use Pherserk\Language\model\LanguageInterface;
use Pherserk\ProgressiveTextAnalyzer\component\ProgressiveTextAnalyzer;
use Pherserk\SignProvider\component\SignProviderInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class ProgressiveTextAnalyzerTest extends TestCase
{

    /**
     * @dataProvider provideLanguageAndTextAndResults
     */
    public function testGetSignAnalysis(string $text) 
    {
        $signProvider = $this->prophesize(SignProviderInterface::class);

        $language = $this->prophesize(LanguageInterface::class);
        $language = $language->reveal();

        $signProvider->search(Argument::any(), $language, 10)
            ->willReturn([]);

        $analyzer = new ProgressiveTextAnalyzer($signProvider->reveal(), 10);

        $results = $analyzer->getSignAnalysis($text, $language);        
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

