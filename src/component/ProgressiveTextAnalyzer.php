<?php

namespace Pherserk\ProgressiveTextAnalyzer\component;

use Pherserk\Language\model\LanguageInterface;
use Pherserk\SignExtractor\component\SignExtractor;
use Pherserk\SignExtractor\model\UnclassifiedSign;
use Pherserk\SignProvider\component\SignProviderInterface;
use Pherserk\SignProvider\model\ClassifiedSign;
use Pherserk\WordExtractor\component\WordExtractor;

class ProgressiveTextAnalyzer
{
    private $signProvider;

    private $minimumClassifications;

    public function __construct(SignProviderInterface $signProvider, int $minimumClassifications) {
        $this->signProvider = $signProvider;
        $this->minimumClassifications = $minimumClassifications;
    }

    /**
     * @param string $test
     * @param LanguageInterface $language
     *
     * @return ClassifiedSign[]|UnclassifiedSign[]
     */
    public function getSignAnalysis(string $text, LanguageInterface $language): array
    {
        $unclassifiedSigns = SignExtractor::extract($text, true);

	$classifiedSigns = $this->signProvider
            ->search(
                $unclassifiedSigns,
                $language, 
                $this->minimumClassifications
            );
        
        // fixme this can easly go (o)ln(n) if optimized with a break
        $signs = [];
        foreach ($unclassifiedSigns as $unclassifiedSign) {
            $sign = $unclassifiedSign;
            foreach ($classifiedSigns as $classifiedSign) {
                if ($unclassifiedSign->getSign() === $classifiedSign->getSign()) {
        	    $sign = $classifiedSign;
         	}
	    }
            $signs[] = $sign;
        }
        
        return $signs;
    }

    /**
     * @param string $text
     * @param ClassifiedSign[] $classifiedSigns
     * @param LanguageInterface $language
     *
     * @return ClassifiedWord[]|UnclassifiedWord[]
     */
    
    public function getWordAnalysis(string $text, array $classifiedSigns, LanguageInterface $language) : array
    {
        $unclassifiedWords = WordExtractor::extract($text, $classifiedSigns, true);    
	
	var_dump($unclassifiedWords);
    }
}

