<?php

namespace Domain\MobileVersion\Requests\Admin;

use Domain\MobileVersion\Enums\MobileVersionPlatformEnum;
use Domain\MobileVersion\Enums\MobileVersionStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MobileVersionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request): array
    {
        return [
            'mobile_version.version' => [
                'required',
                'string',
//                Rule::unique('mobile_versions', 'version')
//                    ->ignore($request->get('mobile_version')['id'])
            ],
            'mobile_version.status' => [
                'nullable',
                'required',
                'string',
                Rule::unique('mobile_versions', 'status')
                    ->where('platform', $this->input('mobile_version.platform'))
                    ->ignore($this->input('mobile_version.id')),
                Rule::in(MobileVersionStatusEnum::toValues())
            ],
            'mobile_version.platform' => [
                'required',
                'string',
                Rule::unique('mobile_versions', 'platform')
                    ->where('status', $this->input('mobile_version.status'))
                    ->ignore($this->input('mobile_version.id')),
                Rule::in(MobileVersionPlatformEnum::toValues())
            ]
        ];
    }

    public function messages()
    {
        $messages = parent::messages();

        return array_merge([
            'unique' => 'Такая платформа со статусом уже существует!',
        ], $messages);
    }
}
