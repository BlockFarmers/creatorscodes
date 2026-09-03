<?php

namespace Azuriom\Plugin\Creatorcodes\Http\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\User;
use Azuriom\Plugin\Creatorcodes\Models\CreatorCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CreatorCodeController extends Controller
{
    public function index(): View
    {
        $codes = CreatorCode::with('creator')
            ->withCount('commissions')
            ->get();

        return view('creatorcodes::admin.index', [
            'codes' => $codes,
        ]);
    }

    public function create(): View
    {
        return view('creatorcodes::admin.create', [
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        CreatorCode::create($this->validateData($request));

        return redirect()->route('creatorcodes.admin.index')
            ->with('success', 'Code createur cree.');
    }

    public function edit(CreatorCode $creatorCode): View
    {
        return view('creatorcodes::admin.edit', [
            'creatorCode' => $creatorCode,
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, CreatorCode $creatorCode): RedirectResponse
    {
        $creatorCode->update($this->validateData($request, $creatorCode->id));

        return redirect()->route('creatorcodes.admin.index')
            ->with('success', 'Code createur mis a jour.');
    }

    public function destroy(CreatorCode $creatorCode): RedirectResponse
    {
        $creatorCode->delete();

        return redirect()->route('creatorcodes.admin.index')
            ->with('success', 'Code createur supprime.');
    }

    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        $request->merge(['code' => strtoupper((string) $request->input('code'))]);

        $uniqueRule = 'unique:creatorcodes_codes,code'.($ignoreId ? ",{$ignoreId}" : '');

        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'code' => ['required', 'string', 'max:50', $uniqueRule],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'paypal_email' => ['nullable', 'email', 'max:191'],
            'active' => ['sometimes'],
        ]);

        $data['active'] = $request->boolean('active');

        return $data;
    }
}
