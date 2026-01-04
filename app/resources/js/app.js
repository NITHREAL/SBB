require('./bootstrap');

import CityFieldController from './controllers/city_field_controller'
import CityRowController from "./controllers/city_row_controller"
import PolygonFieldController from "./controllers/polygon_field_controller"
import FiltersFieldController from "./controllers/filters_field"

application.register('city-field', CityFieldController)
application.register('city-row', CityRowController)
application.register('polygon-field', PolygonFieldController)
application.register('filters-field', FiltersFieldController)
