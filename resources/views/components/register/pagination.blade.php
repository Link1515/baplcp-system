<div class="flex justify-center gap-3 {{ $class }}">
    @for ($i = 0; $i < $steps; $i++)
        <span class="w-1.5 h-1.5 rounded-full {{ $current === $i + 1 ? 'bg-[#428EFF]' : 'bg-neutral-400' }}"></span>
    @endfor
</div>
