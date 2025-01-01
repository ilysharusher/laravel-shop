<select
    x-on:change="$refs.sort.submit()"
    name="sort"
    class="form-select w-full h-12 px-4 rounded-lg border border-body/10 focus:border-pink focus:shadow-[0_0_0_3px_#EC4176] bg-white/5 text-white text-xxs sm:text-xs shadow-transparent outline-0 transition">
    @foreach(sorter()->columns() as $key => $name)
        <option
            @selected(sorter()->requestValue() === $key)
            value="{{ $key }}" class="text-dark">{{ $name }}
        </option>
    @endforeach
</select>
