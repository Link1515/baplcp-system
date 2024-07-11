<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Dates implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $eventDates, Closure $fail): void
    {
        $format = 'Y-m-d';

        foreach ($eventDates as $date) {
            $d = \DateTime::createFromFormat($format, $date);
            if (!($d && $d->format($format) === $date)) {
                // $fail('The :attribute must be a valid date string in the format "Y-m-d, Y-m-d, ..."');
                $fail('validation.eventDates')->translate();
            }
        }
    }
}
