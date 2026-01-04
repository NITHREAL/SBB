@component($typeForm, get_defined_vars())
    <div data-controller="city-field"
         data-city-field-dadata-route="{{$dadataRoute}}">
        <div class="autocomplete">
            <input {{ $attributes }} data-action="keydown->city-field#keydown">
            <ul class="autocomplete-list"></ul>
        </div>
    </div>
@endcomponent
