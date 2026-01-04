<tr>
    @foreach($columns as $column)

        <th class="p-0 align-middle">
            @php
                $columnName = '';

                foreach(explode('.', $column) as $subColumn)
                    $columnName .= "[$subColumn]";
            @endphp

            {!!
               $fields[$column]
                    ->value(Arr::get($row, $column) ?? '')
                    ->prefix($name)
                    ->id("$idPrefix-$key-$column")
                    ->name($keyValue ? $columnName : "[$key]$columnName")
            !!}
        </th>

        @if ($loop->last && $removableRows)
            <th class="no-border text-center align-middle">
                <a href="#"
                   data-action="matrix#deleteRow"
                   class="small text-muted"
                   title="{{ __('Remove row') }}">
                    <x-orchid-icon path="trash"/>
                </a>
            </th>
        @endif
    @endforeach
</tr>
