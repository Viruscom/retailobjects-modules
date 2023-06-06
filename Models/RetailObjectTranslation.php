<?php

namespace Modules\RetailObjects\Models;

use App\Helpers\UrlHelper;
use App\Interfaces\Models\CommonModelTranslationInterfaces;
use App\Models\Language;
use App\Traits\StorageActions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetailObjectTranslation extends Model implements CommonModelTranslationInterfaces
{
    use StorageActions;

    protected $table    = "retail_object_translation";
    protected $fillable = ['locale', 'retail_object_id', 'title', 'url', 'announce', 'description', 'visible', 'address', 'email', 'phone', 'map_iframe'];
    public static function getLanguageArray($language, $request, $modelId, $isUpdate): array
    {
        $data = [
            'locale' => $language->code,
            'title'  => $request['title_' . $language->code],
            'url'    => UrlHelper::generate($request['title_' . $language->code], self::class, $modelId, $isUpdate)
        ];

        if ($request->has('announce_' . $language->code)) {
            $data['announce'] = $request['announce_' . $language->code];
        }

        if ($request->has('description_' . $language->code)) {
            $data['description'] = $request['description_' . $language->code];
        }

        $data['visible'] = false;
        if ($request->has('visible_' . $language->code)) {
            $data['visible'] = filter_var($request['visible_' . $language->code], FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->has('address_' . $language->code)) {
            $data['address'] = $request['address_' . $language->code];
        }

        if ($request->has('email_' . $language->code)) {
            $data['email'] = $request['email_' . $language->code];
        }

        if ($request->has('phone_' . $language->code)) {
            $data['phone'] = $request['phone_' . $language->code];
        }

        if ($request->has('map_iframe_' . $language->code)) {
            $data['map_iframe'] = $request['map_iframe_' . $language->code];
        }

        return $data;
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(RetailObject::class, 'retail_object_id');
    }
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
