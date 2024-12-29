<div>
    <h5 class="mb-4 text-sm 2xl:text-md font-bold">{{ $filter->title() }}</h5>

    @foreach($filter->values() as $id => $title)
        <div class="form-checkbox">
            <input
                name="{{ $filter->name($id) }}"
                value="{{ $id }}"
                type="checkbox"
                id="{{ $filter->id($id) }}"
                @checked($filter->requestValue($id))
            >
            <label for="{{ $filter->id($id) }}" class="form-checkbox-label">
                {{ $title }}
            </label>
        </div>
    @endforeach
</div>
