<label class="flex flex-col gap-1 text-[15px]">
    {{ $slot }}
    <input id="{{ $field }}" x-model.fill="form.{{ $field }}" @keydown.enter.prevent
        type="{{ $type }}" name="{{ $field }}" value="{{ $defaultValue }}"
        class="input @error($field) is-invalid @enderror">
    @error($field)
        <div class="text-error">{{ $message }}</div>
    @enderror
</label>
