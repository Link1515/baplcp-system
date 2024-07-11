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
            'dates' => explode(', ', $this->input('dates')),
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
            'priceForEvent' => 'required|integer',
            'maxParticipantsForEvent' => 'required|integer',
            'eventTime' => 'required',
            'dates' => ['required', new Dates],
            'eventStartRegisterDayBefore' => 'required|integer',
            'eventEndRegisterDayBefore' => 'required|integer',

            'canRegisterAll' => 'accepted',
            'priceForEventGroup' => 'required_with:canRegisterAll|nullable|integer',
            'maxParticipantsForEventGroup' => 'required_with:canRegisterAll|nullable|integer',
            'registerStartDateForEventGroup' => 'required_with:canRegisterAll|nullable|after:now',
            'registerEndDateForEventGroup' => 'required_with:canRegisterAll|nullable|after:registerStartDateForEventGroup',
        ];
    }
}
