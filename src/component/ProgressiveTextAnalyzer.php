<?php

namespace Pherserk\ProgressiveTextAnalyzer\component;

use Pherserk\Language\model\LanguageInterface;
use Pherserk\SignExtractor\component\SignExtractor;
use Pherserk\SignExtractor\model\UnclassifiedSign;
use Pherserk\SignProvider\component\SignProviderInterface;
use Pherserk\SignProvider\model\ClassifiedSign;

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
        
        // fixme this can easly go (o)ln(n)
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
}
