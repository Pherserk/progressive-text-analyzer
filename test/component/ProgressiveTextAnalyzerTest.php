<?php

namespace Pherserk\ProgressiveTextAnalyzer\test\component;

use Pherserk\Language\model\LanguageInterface;
use Pherserk\ProgressiveTextAnalyzer\component\ProgressiveTextAnalyzer;
use Pherserk\SignExtractor\model\UnclassifiedSign;
use Pherserk\SignProvider\component\SignProviderInterface;
use Pherserk\SignProvider\model\ClassifiedSign;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class ProgressiveTextAnalyzerTest extends TestCase
{
    public function testGetSignAnalysis() 
    {
	$text = 'This is a test.';
        $minimumClassifications = 10;       
	$expectation = [
           new ClassifiedSign('T', ClassifiedSign::LETTER_TYPE),
           new UnclassifiedSign('h'),
           new UnclassifiedSign('i'),
           new UnclassifiedSign('s'),
           new UnclassifiedSign(' '),
           new UnclassifiedSign('a'),
           new UnclassifiedSign('t'),
           new UnclassifiedSign('e'),
           new ClassifiedSign('.', ClassifiedSign::TERMINATION_PUNCTATION_TYPE), 
        ];

        $signProvider = $this->prophesize(SignProviderInterface::class);

        $language = $this->prophesize(LanguageInterface::class);
        $language = $language->reveal();

        $signProvider->search(Argument::any(), $language, $minimumClassifications)
            ->willReturn($expectation);

        $analyzer = new ProgressiveTextAnalyzer($signProvider->reveal(), $minimumClassifications);

        $results = $analyzer->getSignAnalysis($text, $language);
	
	static::assertSame($expectation, $results);
    }

    public function testGetWordAnalysis() {
          $text = 'This is a test';
          $minimumClassifications = 10;
          $classifiedSigns = [
            new ClassifiedSign('T', ClassifiedSign::LETTER_TYPE),
            new ClassifiedSign('h', ClassifiedSign::LETTER_TYPE),
            new ClassifiedSign('i', ClassifiedSign::LETTER_TYPE),
            new ClassifiedSign('s', ClassifiedSign::LETTER_TYPE),
            new ClassifiedSign(' ', ClassifiedSign::EMPTY_TYPE),
            new ClassifiedSign('a', ClassifiedSign::LETTER_TYPE),
            new ClassifiedSign('t', ClassifiedSign::LETTER_TYPE),
            new ClassifiedSign('e', ClassifiedSign::LETTER_TYPE),
            new ClassifiedSign('.', ClassifiedSign::TERMINATION_PUNCTATION_TYPE),
         ];

         $language = $this->prophesize(LanguageInterface::class);
         $language = $language->reveal();
         
         $analyzer = new ProgressiveTextAnalyzer($this->prophesize(SignProviderInterface::class)->reveal(), $minimumClassifications);
         $results = $analyzer->getWordAnalysis($text, $classifiedSigns, $language);
    }
}
 
