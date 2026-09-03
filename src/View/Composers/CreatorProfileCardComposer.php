<?php

namespace Azuriom\Plugin\Creatorcodes\View\Composers;

use Azuriom\Extensions\Plugin\UserProfileCardComposer;
use Azuriom\Plugin\Creatorcodes\Models\CreatorSupport;

class CreatorProfileCardComposer extends UserProfileCardComposer
{
    public function getCards()
    {
        if (! auth()->check()) {
            return [];
        }

        $support = CreatorSupport::with('creatorCode.creator')
            ->where('user_id', auth()->id())
            ->first();

        return [
            [
                'name' => 'Code createur',
                'view' => 'creatorcodes::profile-card',
                'data' => ['creatorSupport' => $support],
            ],
        ];
    }
}
