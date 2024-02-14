<?php

namespace modules\test;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TestExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
          new TwigFunction('entrytrans', $this->getTranslations(...)),
        ];
    }

    public function getTranslations($entry) {
        $t=0;
    }
}