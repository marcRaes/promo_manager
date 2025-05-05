<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Entity\User;

class VipPromoExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('vip_promo_message', [$this, 'getVipPromoMessage'], ['is_safe' => ['html']]),
        ];
    }

    public function getVipPromoMessage(User $user): ?string
    {
        if (in_array('ROLE_VIP', $user->getRoles(), true)) {
            return null;
        }

        $remaining = 5 - count($user->getCodes());

        if ($remaining <= 0) {
            return null;
        }

        if ($remaining === 5) {
            return sprintf(
                'Ajouter <strong>%d</strong> code%s promo pour devenir membre <strong>VIP</strong> et accéder à des avantages exclusifs !',
                $remaining,
                $remaining > 1 ? 's' : ''
            );
        }

        return sprintf(
            'Il vous reste <strong>%d</strong> code%s promo à ajouter pour devenir membre <strong>VIP</strong> et accéder à des avantages exclusifs !',
            $remaining,
            $remaining > 1 ? 's' : ''
        );
    }
}
