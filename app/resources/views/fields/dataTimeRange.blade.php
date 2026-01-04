@component($typeForm, get_defined_vars())
    <div class="row" data-controller="datetime"
         data-datetime-allow-input="true"
         data-datetime-range="#end_{{ \Illuminate\Support\Str::slug($attributes['name']) }}">
        <div class="col-md-6 pe-1">
            <div class="form-group">
                <input type="text"
                       @isset($attributes['form']) form="{{ $attributes['form'] ?? null }}" @endisset
                       name="{{ $attributes['name'] }}[start]"
                       id="start_{{ \Illuminate\Support\Str::slug($attributes['name']) }}"
                       value="{{ $value['start'] ?? '' }}"
                       class="form-control">
            </div>
        </div>

        <div class="col-md-6 ps-1">
            <div class="form-group">
                <input type="text"
                       @isset($attributes['form']) form="{{ $attributes['form'] ?? null }}" @endisset
                       name="{{ $attributes['name'] }}[end]"
                       id="end_{{ \Illuminate\Support\Str::slug($attributes['name']) }}"
                       value="{{ $value['end'] ?? '' }}"
                       class="form-control">
            </div>
        </div>
    </div>
@endcomponent
