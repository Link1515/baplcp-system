<label class="flex flex-col gap-1 text-sm">
    {{ $slot }}
    <input id="{{ $field }}" x-model.fill="form.{{ $field }}" @keydown.enter.prevent
        type="{{ $type }}" name="{{ $field }}" value="{{ $defaultValue }}"
        class="input @error($field) is-invalid @enderror">
    @error($field)
        <div class="text-red-600">{{ $message }}</div>
    @enderror
</label>
