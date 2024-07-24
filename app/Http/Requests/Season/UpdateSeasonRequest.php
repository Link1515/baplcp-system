<?php

namespace App\Http\Requests\Season;

use App\Rules\Dates;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSeasonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'canRegisterAllEvents' => (bool) $this->input('canRegisterAllEvents'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'place' => 'required|max:255',
            'singlePrice' => 'required|integer|max:20000|gt:0',
            'totalParticipants' => 'required|integer|max:200|gt:0',
            'nonMemberParticipants' => 'required|integer|max:200|gt:0',

            'canRegisterAllEvents' => 'boolean|nullable',
            'seasonRegisterStartAt' => 'required_if:canRegisterAllEvents,true|nullable|after:now|max:255',
            'seasonRegisterEndAt' => 'required_if:canRegisterAllEvents,true|nullable|after:seasonRegisterStartAt|max:255',
            'registerAllPrice' => 'required_if:canRegisterAllEvents,true|nullable|integer|max:20000|gt:0',
            'registerAllParticipants' => 'required_if:canRegisterAllEvents,true|nullable|integer|max:200|gt:0',
        ];
    }

    // protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    // {
    //     dd($validator->errors());
    //     parent::failedValidation($validator);
    // }
}
