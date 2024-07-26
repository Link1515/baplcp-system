<label class="flex items-center gap-1 cursor-pointer select-none">
    <input x-model.fill="form.{{ $field }}" type="checkbox" name="{{ $field }}" value="{{ $value }}"
        {{ $checked }} class="hidden" />
    <div class="w-5 h-5 border border-[#D1D1D6] rounded grid place-items-center"
        {{ ':' }}class="form.{{ $field }} ? 'bg-primary border-primary' : 'border-[#D1D1D6]'">
        <img src="{{ asset('images/icons/input-checkbox.svg') }}" alt="input-checkbox">
    </div>
    {{ $slot }}
</label>
