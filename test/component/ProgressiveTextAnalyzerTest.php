<?php

namespace Pherserk\ProgressiveTextAnalyzer\test\component;

use Pherserk\Language\model\LanguageInterface;
use Pherserk\ProgressiveTextAnalyzer\component\ProgressiveTextAnalyzer;
use Pherserk\SignProvider\component\SignProviderInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class ProgressiveTextAnalyzerTest extends TestCase
{
    public function testGetSignAnalysis() 
    {
	$text = 'This is a test.';
        $minimumClassifications = 10;       
	$expectation = [];

        $signProvider = $this->prophesize(SignProviderInterface::class);

        $language = $this->prophesize(LanguageInterface::class);
        $language = $language->reveal();

        $signProvider->search(Argument::any(), $language, $minimumClassifications)
            ->willReturn($expectation);

        $analyzer = new ProgressiveTextAnalyzer($signProvider->reveal(), $minimumClassifications);

        $results = $analyzer->getSignAnalysis($text, $language);
	
	static::assertSame($expectation, $results);
    }
}

