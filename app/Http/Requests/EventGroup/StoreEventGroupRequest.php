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
            'eventPrice' => 'required|integer|max:20000',
            'eventMemberParticipants' => 'required|integer|max:200',
            'eventNonMemberParticipants' => 'required|integer|max:200',
            'eventTime' => 'required|max:255',
            'eventDates' => ['required', new Dates, 'max:20'],
            'eventStartRegisterDayBefore' => 'required|integer|max:20',
            'eventStartRegisterDayBeforeTime' => 'required|max:255',
            'eventEndRegisterDayBefore' => 'required|integer|lt:eventStartRegisterDayBefore|max:255',
            'eventEndRegisterDayBeforeTime' => 'required|max:255',

            'canRegisterAllEvent' => 'boolean|nullable',
            'eventGroupPrice' => 'required_if:canRegisterAllEvent,true|nullable|integer|max:20000',
            'eventGroupMaxParticipants' => 'required_if:canRegisterAllEvent,true|nullable|integer|max:20',
            'eventGroupRegisterStartAt' => 'required_if:canRegisterAllEvent,true|nullable|after:now|max:255',
            'eventGroupRegisterEndAt' => 'required_if:canRegisterAllEvent,true|nullable|after:eventGroupRegisterStartAt|max:255',
        ];
    }

    // protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    // {
    //     dd($validator->errors());
    //     parent::failedValidation($validator);
    // }
}
