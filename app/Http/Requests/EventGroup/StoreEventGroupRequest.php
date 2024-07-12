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
            'eventPrice' => 'required|integer',
            'eventMemberParticipants' => 'required|integer',
            'eventNonMemberParticipants' => 'required|integer',
            'eventTime' => 'required',
            'eventDates' => ['required', new Dates],
            'eventStartRegisterDayBefore' => 'required|integer',
            'eventStartRegisterDayBeforeTime' => 'required',
            'eventEndRegisterDayBefore' => 'required|integer|lt:eventStartRegisterDayBefore',
            'eventEndRegisterDayBeforeTime' => 'required',

            'canRegisterAllEvent' => 'boolean|nullable',
            'eventGroupPrice' => 'required_if:canRegisterAllEvent,true|nullable|integer',
            'eventGroupMaxParticipants' => 'required_if:canRegisterAllEvent,true|nullable|integer',
            'eventGroupRegisterStartAt' => 'required_if:canRegisterAllEvent,true|nullable|after:now',
            'eventGroupRegisterEndAt' => 'required_if:canRegisterAllEvent,true|nullable|after:eventGroupRegisterStartAt',
        ];
    }

    // protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    // {
    //     dd($validator->errors());
    //     parent::failedValidation($validator);
    // }
}
