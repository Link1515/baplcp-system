<?php

namespace App\Http\Requests\EventGroup;

use App\Rules\Dates;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventGroupRequest extends FormRequest
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
            'eventDates' => explode(', ', $this->input('eventDates')),
            'canRegisterAllEvent' => (bool) $this->input('canRegisterAllEvent'),
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
            'subTitle' => 'required|max:255',
            'singlePrice' => 'required|integer|max:20000|gt:0',
            'memberParticipants' => 'required|integer|max:200|gt:0',
            'nonMemberParticipants' => 'required|integer|max:200|gt:0',

            'eventTime' => 'required|max:255',
            'eventDates' => ['required', new Dates, 'max:30'],
            'eventStartRegisterDayBefore' => 'required|integer|max:20|gt:0',
            'eventStartRegisterDayBeforeTime' => 'required|max:255',
            'eventEndRegisterDayBefore' => 'required|integer|lt:eventStartRegisterDayBefore|max:255|gt:0',
            'eventEndRegisterDayBeforeTime' => 'required|max:255',

            'canRegisterAllEvent' => 'boolean|nullable',
            'eventGroupRegisterStartAt' => 'required_if:canRegisterAllEvent,true|nullable|after:now|max:255',
            'eventGroupRegisterEndAt' => 'required_if:canRegisterAllEvent,true|nullable|after:eventGroupRegisterStartAt|max:255',
            'registerAllPrice' => 'required_if:canRegisterAllEvent,true|nullable|integer|max:20000|gt:0',
            'registerAllParticipants' => 'required_if:canRegisterAllEvent,true|nullable|integer|max:200|gt:0',
        ];
    }

    // protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    // {
    //     dd($validator->errors());
    //     parent::failedValidation($validator);
    // }
}
