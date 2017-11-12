<?php

namespace Pherserk\ProgressiveTextAnalyzer\component;

use Pherserk\SignExtractor\component\SignExtractor;

class ProgressiveTextAnalyzer
{
    public function getSignAnalysis(string $text)
    {
        $signs = SignExtractor::extract($text, true);
	var_dump($signs);        
    }
}
