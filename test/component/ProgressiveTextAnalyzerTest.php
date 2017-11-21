<?php

namespace Pherserk\ProgressiveTextAnalyzer\test\component;

use Pherserk\Language\model\LanguageInterface;
use Pherserk\ProgressiveTextAnalyzer\component\ProgressiveTextAnalyzer;
use Pherserk\SignExtractor\model\UnclassifiedSign;
use Pherserk\SignProvider\component\SignProviderInterface;
use Pherserk\SignProvider\model\ClassifiedSign;
use Pherserk\WordExtractor\model\UnclassifiedWord;
use Pherserk\WordProvider\component\WordProviderInterface;
use Pherserk\WordProvider\model\en\ClassifiedWord as EnglishClassifiedWord;
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
            ->willReturn(
                [
                    new ClassifiedSign('T', ClassifiedSign::LETTER_TYPE),
                    new ClassifiedSign('.', ClassifiedSign::TERMINATION_PUNCTATION_TYPE),
                ]
            );

        $analyzer = new ProgressiveTextAnalyzer(
            $signProvider->reveal(), 
            $this->prophesize(WordProviderInterface::class)->reveal(), 
            $minimumClassifications
        );

        $results = $analyzer->getSignAnalysis($text, $language);
	
	static::assertEquals($expectation, $results);
    }

    public function testGetWordAnalysis() {
          $language = $this->prophesize(LanguageInterface::class);
          $language->getIso639Alpha2Code()->willReturn('en');
          $language = $language->reveal();

          $expectation = [
              new EnglishClassifiedWord('This', $language, EnglishClassifiedWord::PRONOUN_TYPE),
              new UnclassifiedWord('is'),
              new UnclassifiedWord('a'),
              new EnglishClassifiedWord('test', $language, EnglishClassifiedWord::NAME_TYPE),
          ];

          $text = 'This is a test.';
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

         $classifiedWords = [
             new EnglishClassifiedWord('This', $language, EnglishClassifiedWord::PRONOUN_TYPE),
             new EnglishClassifiedWord('test', $language, EnglishClassifiedWord::NAME_TYPE),
         ];

         $wordProvider = $this->prophesize(WordProviderInterface::class);
	 $wordProvider->search(
                 Argument::any(),
                 $language,
		 $minimumClassifications
	     )->willReturn($classifiedWords);

         $analyzer = new ProgressiveTextAnalyzer(
             $this->prophesize(SignProviderInterface::class)->reveal(), 
             $wordProvider->reveal(), 
             $minimumClassifications
         );

         $results = $analyzer->getWordAnalysis($text, $classifiedSigns, $language);
	 
         static::assertEquals($expectation, $results);
     }

     public function testGetTextAnalysis()
     {
	  $expectation = [
              new ClassifiedSign('T', ClassifiedSign::LETTER_TYPE),
              new UnclassifiedSign('h'),
              new UnclassifiedSign('i'),
              new UnclassifiedSign('s'),
              new UnclassifiedSign(' '),
              new UnclassifiedSign('a'),
              new UnclassifiedSign('t'),
              new UnclassifiedSign('e'),
              new UnclassifiedSign('.'),
          ];

          $language = $this->prophesize(LanguageInterface::class);
          $language->getIso639Alpha2Code()->willReturn('en');
          $language = $language->reveal();

	  $signProvider = $this->prophesize(SignProviderInterface::class);
          $signProvider->search(Argument::any(), $language, 10)->willReturn(
	      [
                  new ClassifiedSign('T', ClassifiedSign::LETTER_TYPE), 
              ]
          );

          $wordProvider = $this->prophesize(WordProviderInterface::class);
	  $wordProvider->search(Argument::any(), $language, 10)->willReturn([]);

          $analyzer = new ProgressiveTextAnalyzer(
              $signProvider->reveal(),
              $wordProvider->reveal(),
              10
          );

          $results = $analyzer->getTextAnalysis('This is a test.', $language);

          static::assertEquals($expectation, $results);
     }
}
 
