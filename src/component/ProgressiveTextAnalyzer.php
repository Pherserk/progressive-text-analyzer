<?php

namespace Pherserk\ProgressiveTextAnalyzer\component;

use Pherserk\Language\model\LanguageInterface;
use Pherserk\SignExtractor\component\SignExtractor;
use Pherserk\SignProvider\component\SignProviderInterface;

class ProgressiveTextAnalyzer
{
    private $signProvider;

    public function __construct(SignProviderInterface $signProvider)
    {
        $this->signProvider = $signProvider;
    }

    public function getSignAnalysis(LanguageInterface $language, string $text)
    {
        $signs = SignExtractor::extract($text, true);
        
    }
}
