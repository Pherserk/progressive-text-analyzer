<?php

namespace Pherserk\ProgressiveTextAnalyzer\component;

use Pherserk\Language\model\LanguageInterface;
use Pherserk\SignExtractor\component\SignExtractor;
use Pherserk\SignProvider\component\SignProviderInterface;

class ProgressiveTextAnalyzer
{
    private $signProvider;

    private $minimumClassifications;

    public function __construct(SignProviderInterface $signProvider, int $minimumClassifications) {
        $this->signProvider = $signProvider;
        $this->minimumClassifications = $minimumClassifications;
    }

    public function getSignAnalysis(string $text, LanguageInterface $language)
    {
        $signs = SignExtractor::extract($text, true);
	$this->signProvider->search($signs, $language, $this->minimumClassifications);
    }
}
