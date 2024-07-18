<?php

namespace App\Http\Requests\EventRegistration;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRegistrationRequest extends FormRequest
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
            'memberRegister' => (bool) $this->input('memberRegister'),
            'nonMemberRegister' => (bool) $this->input('nonMemberRegister'),
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
            'eventId' => 'required|integer',
            'memberRegister' => 'boolean',
            'nonMemberRegister' => 'boolean',
            'nonMemberName' => 'required_if:nonMemberRegister,true'
        ];
    }
}
