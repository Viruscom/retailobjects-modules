<?php

namespace Modules\RetailObjects\Http\Requests;

use App\Helpers\LanguageHelper;
use Illuminate\Foundation\Http\FormRequest;

class RetailObjectsUpdateRequest extends FormRequest
{
    public function __construct()
    {
        $this->LANGUAGES = LanguageHelper::getActiveLanguages();
    }
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        $this->trimInput();
        $array = [];

        foreach ($this->LANGUAGES as $language) {
            $array['title_' . $language->code]      = 'required';
            $array['address_' . $language->code]    = 'required';
            $array['phone_' . $language->code]      = 'required';
            $array['email_' . $language->code]      = 'required';
            $array['map_iframe_' . $language->code] = 'required';
        }

        return $array;
    }
    public function trimInput()
    {
        $trim_if_string = function ($var) {
            return is_string($var) ? trim($var) : $var;
        };
        $this->merge(array_map($trim_if_string, $this->all()));
    }
    public function messages()
    {
        $messages = [];
        foreach ($this->LANGUAGES as $language) {
            $messages['title_' . $language->code . '.required']      = 'Полето за заглавие (' . $language->code . ') е задължително';
            $messages['address_' . $language->code . '.required']    = 'Полето за адрес (' . $language->code . ') е задължително';
            $messages['phone_' . $language->code . '.required']      = 'Полето за телефон (' . $language->code . ') е задължително';
            $messages['email_' . $language->code . '.required']      = 'Полето за e-mail (' . $language->code . ') е задължително';
            $messages['map_iframe_' . $language->code . '.required'] = 'Полето за сорс към Google карта (' . $language->code . ') е задължително';
        }

        return $messages;
    }
}
