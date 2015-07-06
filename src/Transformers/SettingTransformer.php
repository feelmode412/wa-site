<?php
namespace Webarq\Site\Transformers;

use Webarq\Site\Models\Setting;
use League\Fractal;

class SettingTransformer extends Fractal\TransformerAbstract
{
    public function transform(Setting $setting)
    {
        return [
            'id' => (int) $setting->id,
            'code' => $setting->code,
            'type' => $setting->type,
            'value' => $setting->value,
        ];
    }
}
