<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Entity\Code;

class PromoExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('promo_state_classes', [$this, 'getPromoStateClasses']),
            new TwigFunction('promo_state_label', [$this, 'getPromoStateLabel']),
            new TwigFunction('promo_state_label_classes', [$this, 'getPromoStateLabelClasses']),
        ];
    }

    public function getPromoStateClasses(Code $code): string
    {
        $timestampNow = time();
        $from = $code->getValidFrom()->getTimestamp();
        $until = $code->getValidUntil()->getTimestamp();

        $isActive = $from <= $timestampNow && $until >= $timestampNow;
        $isFuture = $from > $timestampNow;
        $isExpired = $until < $timestampNow;
        $isVip = $code->isVipOnly();

        if ($isVip) {
            return 'bg-yellow-50';
        }

        if ($isActive) {
            return 'bg-green-50';
        }

        if ($isFuture) {
            return 'bg-blue-50';
        }

        if ($isExpired) {
            return 'bg-red-200 text-gray-500';
        }

        return '';
    }

    public function getPromoStateLabel(Code $code): string
    {
        $timestampNow = time();
        $from = $code->getValidFrom()->getTimestamp();
        $until = $code->getValidUntil()->getTimestamp();

        $isActive = $from <= $timestampNow && $until >= $timestampNow;
        $isFuture = $from > $timestampNow;
        $isExpired = $until < $timestampNow;

        if ($isActive) {
            return 'Actif';
        }

        if ($isFuture) {
            return 'Bientôt';
        }

        if ($isExpired) {
            return 'Expiré';
        }

        return '';
    }

    public function getPromoStateLabelClasses(Code $code): string
    {
        $state = $this->getPromoStateLabel($code);

        return match ($state) {
            'Actif' => 'text-green-800 bg-green-200',
            'Bientôt' => 'text-yellow-800 bg-yellow-200',
            'Expiré' => 'text-red-800 bg-red-500',
            default => '',
        };
    }
}
