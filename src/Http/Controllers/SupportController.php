<?php

namespace Azuriom\Plugin\Creatorcodes\Http\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Creatorcodes\Models\CreatorCode;
use Azuriom\Plugin\Creatorcodes\Models\CreatorSupport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function show(): View
    {
        $support = CreatorSupport::with('creatorCode.creator')
            ->where('user_id', auth()->id())
            ->first();

        return view('creatorcodes::support', [
            'support' => $support,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $code = strtoupper($request->input('code'));

        $creatorCode = CreatorCode::where('code', $code)
            ->where('active', true)
            ->first();

        if (! $creatorCode) {
            return back()->withErrors(['code' => "Ce code createur n'existe pas ou n'est plus actif."]);
        }

        if ($creatorCode->user_id === auth()->id()) {
            return back()->withErrors(['code' => 'Tu ne peux pas te soutenir toi-meme.']);
        }

        CreatorSupport::updateOrCreate(
            ['user_id' => auth()->id()],
            ['creator_code_id' => $creatorCode->id]
        );

        return back()->with('success', 'Createur soutenu avec succes : '.$creatorCode->code);
    }

    public function destroy(): RedirectResponse
    {
        CreatorSupport::where('user_id', auth()->id())->delete();

        return back()->with('success', 'Tu ne soutiens plus aucun createur.');
    }
}
