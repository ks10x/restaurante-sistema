<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRestaurantConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $hexColor = ['required', 'regex:/^#[A-Fa-f0-9]{6}$/'];

        return [
            'logo' => ['nullable', 'image', 'max:2048'],
            'primary_color' => $hexColor,
            'secondary_color' => $hexColor,
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['required', 'string', 'max:255'],
            'delivery_time' => ['required', 'string', 'max:255'],
            'location_text' => ['required', 'string', 'max:255'],
            'rating_score' => ['required', 'numeric', 'between:0,9.9'],
            'rating_label' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'logo' => 'logo',
            'primary_color' => 'cor primaria',
            'secondary_color' => 'cor secundaria',
            'hero_title' => 'titulo principal',
            'hero_subtitle' => 'subtitulo principal',
            'delivery_time' => 'tempo de entrega',
            'location_text' => 'texto de localizacao',
            'rating_score' => 'nota',
            'rating_label' => 'rotulo da avaliacao',
        ];
    }
}
