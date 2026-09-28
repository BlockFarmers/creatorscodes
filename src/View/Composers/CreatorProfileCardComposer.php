<?php

namespace Azuriom\Plugin\CreatorsCodes\View\Composers;

use Azuriom\Extensions\Plugin\UserProfileCardComposer;
use Azuriom\Plugin\CreatorsCodes\Models\CreatorSupport;

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
                'name' => 'Creators Codes',
                'view' => 'creatorscodes::profile-card',
                'data' => ['creatorSupport' => $support],
            ],
        ];
    }
}
