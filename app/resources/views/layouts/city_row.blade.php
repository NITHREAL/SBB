<fieldset class="mb-3" data-async
          data-controller="city-row"
          data-city-row-title-name="{{$titleName}}"
          data-city-row-fias-id-name="{{$fiasIdName}}"
          data-city-row-latitude-name="{{$latitudeName}}"
          data-city-row-longitude-name="{{$longitudeName}}"
          data-city-row-is-settlement-name="{{$isSettlementName}}"
>
    @empty(!$title)
        <div class="col p-0 px-3">
            <legend class="text-black">
                {{ $title }}
            </legend>
        </div>
    @endempty

    <div class="bg-white rounded shadow-sm p-4 py-4 d-flex flex-column">
        {!! $form ?? '' !!}
    </div>
</fieldset>
