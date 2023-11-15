<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $validators = [
            'youtube' => ['required'],
            'instagram' => ['required'],
            'facebook' => ['required'],
            'linkedin' => ['required'],
            'twitter' => ['required'],
            'tikTok' => ['required'],
            'messenger' => ['required'],
            'whatsApp' => ['required', 'numeric'],
            'phone' => ['required', 'numeric'],
            'email' => ['required'],
        ];
        foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $validators['title-' . $localeCode] = ['required'];
        }
        return $validators;
    }
}
