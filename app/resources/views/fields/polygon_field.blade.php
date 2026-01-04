@component($typeForm, get_defined_vars())
    <div data-controller="polygon-field"
         data-polygon-field-api-key="{{ $apiKey }}"
         data-polygon-field-field-name="{{ $name }}"
         data-polygon-field-store="{{ $store }}"
         data-polygon-field-other-polygons="{{ $otherPolygons }}"
         data-polygon-field-types="{{ $types }}"
         data-polygon-field-value="{{ is_string($value) ? $value : json_encode($value, JSON_THROW_ON_ERROR) }}">

        <!-- Описание с HTML-тегами -->
        <div>
            <p><strong>Существует три вида полигонов:</strong></p>
            <ol>
                <li><strong>Быстрая доставка:</strong> при настройке такого полигона доставка будет на ближайший из доступных интервалов. У пользователя не будет выбора времени для доставки.</li>
                <li><strong>Расширенный ассортимент:</strong> при настройке такого полигона выбирается доставка на сегодня. У пользователя будет выбор интервалов.</li>
                <li><strong>Доставка на другой день:</strong> при настройке такого полигона выбирается доставка на другой день. У пользователя будет выбор интервалов.</li>
            </ol>
            <p><strong>Рекомендации:</strong></p>
            <ul>
                <li>Необходимо избегать полного совпадения границ полигонов (должен быть хоть минимальный отступ от границ).</li>
                <li>Полигоны меньшего размера желательно настраивать поверх полигонов большего размера.</li>
            </ul>
        </div>

        <div id="yandex-map" style="min-height: 500px;"></div>
        <div data-polygon-field-target="polygons"></div>
    </div>
@endcomponent

