<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateRestaurantConfigRequest;
use App\Models\RestaurantConfig;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function update(UpdateRestaurantConfigRequest $request)
    {
        $user = $request->user();
        $config = RestaurantConfig::forUser($user);

        $data = $request->safe()->except('logo');
        $data['primary_color'] = '#FFFFFF';

        if ($request->hasFile('logo')) {
            if ($config->logo_path) {
                Storage::disk('public')->delete($config->logo_path);
            }

            $data['logo_path'] = $request->file('logo')->store('restaurants/logos', 'public');
        }

        $config->fill($data);
        $config->save();
        $config->flushCache();

        return redirect()
            ->route('admin.configuracoes.index')
            ->with('success', 'Identidade visual atualizada com sucesso.');
    }
}
