@foreach ($images as $image)
    <div class="d-inline-block rounded bg-white mb-3 p-3">
        <div class="border-dashed d-flex align-items-center rounded overflow-hidden">
            <img class="mw-100" src="{{$image->url}}?w=200&h=200">
        </div>
    </div>
@endforeach
