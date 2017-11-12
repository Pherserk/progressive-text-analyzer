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
     *
     * @return ClassifiedSign[]|UnclassifiedSign[]
     */
    public function getSignAnalysis(string $text, LanguageInterface $language): array
    {
	return $this->signProvider
            ->search(
                SignExtractor::extract($text, true),
                $language, 
                $this->minimumClassifications
            );
    }
}
