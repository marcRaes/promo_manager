<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FlashColorExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('flash_color_class', [$this, 'getFlashColor']),
        ];
    }

    public function getFlashColor(string $label): string
    {
        return match ($label) {
            'success' => 'bg-green-100 text-green-800 border-green-300',
            'error' => 'bg-red-100 text-red-800 border-red-300',
            'info' => 'bg-blue-100 text-blue-800 border-blue-300',
            default => 'bg-yellow-100 text-yellow-800 border-yellow-300',
        };
    }
}
