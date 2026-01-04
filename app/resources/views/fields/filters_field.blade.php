<?php

use Domain\Audience\Models\Audience;

get_defined_vars();

/** @var Audience $audience */

?>

<div class="bg-white rounded shadow-sm p-4 py-4 d-flex flex-column">
    <div data-controller="filters-field">
        <div id="el">
            <?php
            $filter_data = $audience->filter_data[1] ?? [];
            ?>

            <div class="form-group row">
                <input type="hidden" name="audience[filter_data][1][name]" value="check_avg">

                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="defaultCheck2"
                               name="audience[filter_data][1][enable]"
                               value="1"
                               @if ($filter_data['enable'] ?? false)
                                   checked
                            @endif
                        >
                        <label class="form-check-label" for="defaultCheck2">
                            Средний чек
                        </label>
                    </div>
                </div>
                <div class="col-md-1">

                    <select class="form-control" name="audience[filter_data][1][condition]">
                        <option value="ge" @selected(Arr::get($filter_data, 'condition') == "ge")>от</option>
                        <option value="le" @selected(Arr::get($filter_data, 'condition') == "le")>до</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input class="form-control" type="number" step="1" value="{{ $filter_data['value'] ?? 0}}"
                           name="audience[filter_data][1][value]">
                </div>
            </div>
            {{--<input data-filters-field-target="name" v-model="name"  type="text"  >--}}

            {{--<button data-action="click->filters-field#greet" @click="message = name">--}}
            {{--    Greet--}}
            {{--</button>--}}

            {{--<span data-filters-field-target="output">  </span>--}}

            {{--<h1 id="#el">--}}
            {{--    @{{ message }}--}}
            {{--</h1>--}}
        </div>
    </div>
</div>
