import ymaps from 'ymaps';

// todo проверка на точку
// todo init внутри полигона
export default class extends window.Controller {
    static targets = [ 'polygons' ]

    _map
    _components
    _currentPolygon
    _value
    _store
    _types
    _fillColorButton
    _keys = []

    async connect() {
        await this.loadMap()
        this._value = JSON.parse(this.data.get('value'))
        this._store = JSON.parse(this.data.get('store'))
        this._types = JSON.parse(this.data.get('types'))

        this.initControlButton()
        this.initPolygons()
    }

    async loadMap() {
        let store = JSON.parse(this.data.get('store'));
        this._components = await ymaps.load('//api-maps.yandex.ru/2.1/?apikey=' + this.data.get('api-key') + '&lang=ru_RU')
        this._map = new this._components.Map('yandex-map', {
            center: [store.latitude, store.longitude],
            zoom: 10
        }, {
            searchControlProvider: 'yandex#search'
        })
    }

    initControlButton() {
        this.initButtonFillColor()
        this.initButtonDelete()
        this.initButtonAddPolygon()
    }

    initButtonDelete() {
        let buttonDelete = new this._components.control.Button({
            data: {
                content: 'Удалить'
            },
            options: {
                selectOnClick: false
            }
        });
        buttonDelete.events.add('click', () => {
            if (this._currentPolygon) {
                const index = this._map.geoObjects.indexOf(this._currentPolygon);
                this._map.geoObjects.remove(this._currentPolygon);
                this._currentPolygon = null;

                this.updatePolygonIndexes();  // обновляем индексы

                let polygonInputs = this.polygonsTarget.getElementsByTagName('input');
                polygonInputs = [...polygonInputs];
                let input = polygonInputs.find((element) => {
                    return parseInt(element.dataset.index, 10) === index;
                });

                if (input) {
                    input.remove();
                }

                this.updatePolygonIndexes();  // обновляем индексы еще раз после удаления
            }
        });
        this._map.controls.add(buttonDelete);
    }
    updatePolygonIndexes() {
        let polygonInputs = this.polygonsTarget.getElementsByTagName('input');
        polygonInputs = [...polygonInputs];
        for (let i = 0; i < polygonInputs.length; i++) {
            polygonInputs[i].dataset.index = i;
        }
        // Обновляем индексы в geoObjects
        for (let i = 0; i < this._map.geoObjects.getLength(); i++) {
            const geoObject = this._map.geoObjects.get(i);
            if (geoObject) {
                geoObject.properties.set('index', i);
            }
        }
    }


    initButtonAddPolygon() {
        let buttonAddPolygon = new this._components.control.Button({data: {content: 'Полигон'}})
        buttonAddPolygon.events.add('press', () => {
            if (!buttonAddPolygon.isSelected()) {
                let polygon = this.addPolygon([], {
                        store: this._store,
                        delivery_prices: [],
                        types: this._types,
                        type: null
                    },
                    {
                        drawing: true,
                        draggable: false
                    }
                )

                polygon.editor.startDrawing()
                this._currentPolygon = polygon
                const color = polygon.options.get('fillColor')
                this._fillColorButton.options.set('color', '#' + color.slice(0, color.length - 2))
            } else if(this._currentPolygon) {
                this._currentPolygon.editor.stopDrawing()
            }
        })
        this._map.controls.add(buttonAddPolygon)
    }

    initButtonFillColor() {
        let fillColorLayout = this._components.templateLayoutFactory.createClass(
            '<div class="control-button-color">' +
            '<button type="button" class="button-fill">{{data.title}}</button>' +
            '<input type="color" value="{{ options.color }}" class="input-fill hidden"/>' +
            '</div>',
            {
                build: function() {
                    fillColorLayout.superclass.build.call(this);

                    let button = document.querySelector('.control-button-color .button-fill');
                    let colorInput = document.querySelector('.control-button-color input[type="color"]');

                    // Обработчик для кнопки, чтобы показывать/скрывать инпут
                    button.addEventListener('click', () => {
                        colorInput.classList.toggle('hidden');  // Показываем/скрываем input
                    });

                    // Обработчик для изменения цвета
                    colorInput.addEventListener('change', (e) => {
                        this.getData().options.set('color', e.target.value);
                        button.style.backgroundColor = e.target.value + '99';  // Обновляем цвет кнопки
                    });
                },
                clear: function () {
                    let button = document.querySelector('.control-button-color input[type="color"]');
                    button.removeEventListener('change', (e) => {
                        this.getData().options.set('color', e.target.value);
                    });

                    fillColorLayout.superclass.clear.call(this);
                }
            }
        );

        let buttonFillColor = new this._components.control.Button({
            data: {
                title: 'Заливка'
            },
            options: {
                maxWidth: 150,
                selectOnClick: false,
                layout: fillColorLayout,
                color: '#000000',
            }
        });

        this._map.controls.add(buttonFillColor);

        buttonFillColor.options.events.add('change', (e) => {
            if (this._currentPolygon) {
                const options = e.get('target');
                const color = options.get('color');
                this._currentPolygon.options.set('fillColor', color.slice(1) + '99');
                this._currentPolygon.options.set('strokeColor', color.slice(1) + 'ff');
            }
        });

        this._fillColorButton = buttonFillColor;
    }

    addPolygon(coordinates = [], properties = {}, options= {
        drawing: false,
        draggable: false
    }) {
        let polygon = this.initializePolygon(coordinates, properties, options);

        if (options.drawing) {
            this.setPolygonTemplate(polygon);

            polygon.editor.options.set('menuManager',
                function (menuItems) {
                    for (let i = 0; i < menuItems.length; i++) {
                        if (menuItems[i].id === 'addInterior') {
                            menuItems.splice(i, 1);
                        }
                    }
                    return menuItems;
                }
            )
            this.setPolygonEventHandlers(polygon);

            // this.addEventCheckIntersect(polygon)
            this.addEventChangeOptions(polygon)
        }

        return polygon
    }

    initializePolygon(coordinates, properties, options) {
        let polygon = new this._components.Polygon(coordinates, properties, options);
        this._map.geoObjects.add(polygon);
        return polygon;
    }

    setPolygonTemplate(polygon) {
        const that = this
        let polygonBalloonLayout = this._components.templateLayoutFactory.createClass(
            '<div class="polygon-balloon">' +
            '<div class="polygon-balloon__header">{{ properties.store.title }}</div>' +
            '<div class="polygon-balloon__address">{{ properties.store.address }}</div>' +
            '<div class="polygon-balloon__types">' +
            '{% for key, type in properties.types %}' +
            '{% if properties.type == key %}' +
            '<input class="type-delivery__input" type="radio" id="type_{{key}}" name="type" value="{{ key }}" checked="true">\n' +
            '{% else %}' +
            '<input class="type-delivery__input" type="radio" id="type_{{key}}" name="type" value="{{ key }}">\n' +
            '{% endif %}' +
            '<label for="type_{{key}}">{{ type }}</label><br>' +
            '{% endfor %}' +
            '</div>' +
            '<table class="polygon-balloon__table delivery-prices-table">' +
            '<thead>' +
            '<tr>' +
            '<th>от</th>' +
            '<th>до</th>' +
            '<th>Стоимость</th>' +
            '<th></th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>' +
            '{% for key, price in properties.delivery_prices %}' +
            '<tr class="delivery-prices-table__row delivery-price" data-key="{{ key }}" data-id="{{ price.id }}">' +
            '<td>' +
            '<input class="delivery-prices-table__input delivery-price__field-from" data-field="from" type="number" min="0" step="0.01" value="{{ price.from }}">' +
            '</td>' +
            '<td>' +
            '<input class="delivery-prices-table__input delivery-price__field-to" data-field="to" type="number" min="0" step="0.01" value="{{ price.to }}">' +
            '</td>' +
            '<td>' +
            '<input class="delivery-prices-table__input delivery-price__field-price" data-field="price" type="number" min="0" step="0.01" value="{{ price.price }}">' +
            '</td>' +
            '<td class="text-center delivery-prices-table__delete-row">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32">' +
            '<path d="M28.025 4.97l-7.040 0v-2.727c0-1.266-1.032-2.265-2.298-2.265h-5.375c-1.267 0-2.297 0.999-2.297 2.265v2.727h-7.040c-0.552 0-1 0.448-1 1s0.448 1 1 1h1.375l2.32 23.122c0.097 1.082 1.019 1.931 2.098 1.931h12.462c1.079 0 2-0.849 2.096-1.921l2.322-23.133h1.375c0.552 0 1-0.448 1-1s-0.448-1-1-1zM13.015 2.243c0-0.163 0.133-0.297 0.297-0.297h5.374c0.164 0 0.298 0.133 0.298 0.297v2.727h-5.97zM22.337 29.913c-0.005 0.055-0.070 0.11-0.105 0.11h-12.463c-0.035 0-0.101-0.055-0.107-0.12l-2.301-22.933h17.279z"></path>' +
            '</svg>' +
            '</td>' +
            '</tr>' +
            '{% endfor %}' +
            '<tr>' +
            '<td colspan=4 class="text-center">' +
            '<button class="delivery-prices-table__add-row">' +
            '+ Добавить' +
            '</button>' +
            '</td>' +
            '</tr>' +
            '</tbody>' +
            '</table>' +
            '<button class="polygon-balloon__save-button">Готово</button>' +
            '</div>'
            , {
                build: function() {
                    polygonBalloonLayout.superclass.build.call(this);
                    let parentElement = this.getParentElement()
                    const addRowButton = parentElement.getElementsByClassName('delivery-prices-table__add-row')[0]
                    addRowButton.addEventListener('click', () => {
                        let deliveryPrices = this.getData().properties.get('delivery_prices')
                        deliveryPrices.push({
                            from: 0,
                            to: 0,
                            price: 0
                        })

                        this._data.properties._data.delivery_prices = deliveryPrices
                        this.rebuild()
                    })

                    document.addEventListener('click', (e) => {
                        let item;
                        if (item = e.target.closest('.delivery-prices-table__delete-row')) {
                            let deliveryPrices = this.getData().properties.get('delivery_prices')
                            if (that._keys.indexOf(item.parentElement.dataset.id) === -1) {
                                deliveryPrices.splice(item.parentElement.dataset.key, 1)
                                that._keys.push(item.parentElement.dataset.id)
                                this._data.properties._data.delivery_prices = deliveryPrices
                                this.rebuild()
                            }
                        }
                        else if (item = e.target.closest('.polygon-balloon__save-button')) {
                            this.getData().map.balloon.close()
                        }
                    })
                    function updateDeliveryPrices(key, field, value) {
                        let deliveryPrices = this.getData().properties.get('delivery_prices');
                        deliveryPrices[key][field] = value;
                        this._data.properties._data.delivery_prices = deliveryPrices;
                    }

                    function updateType(value) {
                        this._data.properties._data.type = value;
                    }

                    document.addEventListener('change', (e) => {
                        let item;
                        e.target.closest('.delivery-prices-table__input')
                        if (item =  e.target.closest('.delivery-prices-table__input')) {
                            updateDeliveryPrices.call(this, item.parentElement.parentElement.dataset.key, item.dataset.field, item.value);
                        }
                        else if (item = e.target.closest('.type-delivery__input')) {
                            updateType.call(this, item.value);
                        }
                    })
                },
                clear: function () {
                    let parentElement = this.getParentElement()
                    const addRowButton = parentElement.getElementsByClassName('delivery-prices-table__add-row')[0]
                    addRowButton.removeEventListener('click', () => {
                        let deliveryPrices = this.getData().properties.get('delivery_prices')
                        deliveryPrices.push({
                            from: 0,
                            to: 0,
                            price: 0
                        })

                        this._data.properties._data.delivery_prices = deliveryPrices
                        this.rebuild()
                    })

                    document.removeEventListener('click', (e) => {
                        let item = e.target.closest('.delivery-prices-table__delete-row')
                        if (item) {
                            let deliveryPrices = this.getData().properties.get('delivery_prices')
                            deliveryPrices.splice(item.parentElement.dataset.key, 1)
                            this._data.properties._data.delivery_prices = deliveryPrices
                            this.rebuild()
                        }
                    })

                    document.removeEventListener('click', (e) => {
                        if (e.target.closest('.polygon-balloon__save-button')) {
                            this.getData().map.balloon.close()
                        }
                    })

                    document.removeEventListener('change', (e) => {
                        let item = e.target.closest('.delivery-prices-table__input')
                        if (item) {
                            let deliveryPrices = this.getData().properties.get('delivery_prices')
                            deliveryPrices[item.parentElement.parentElement.dataset.key][item.dataset.field] = item.value
                            this._data.properties._data.delivery_prices = deliveryPrices
                        }
                    })

                    document.removeEventListener('change', (e) => {
                        let item = e.target.closest('.type-delivery__input')
                        if (item) {
                            this._data.properties._data.type = item.value
                        }
                    })
                    polygonBalloonLayout.superclass.clear.call(this);
                }
            })

        polygon.options.set('balloonContentLayout', polygonBalloonLayout)
    }

    setPolygonEventHandlers(polygon) {
        polygon.events.add('click', (e) => {
            let currentPolygon = e.originalEvent.target;
            if (this._currentPolygon !== currentPolygon) {
                this._currentPolygon = e.originalEvent.target;
            }
            const color = currentPolygon.options.get('fillColor');
            this._fillColorButton.options.set('color', '#' + color.slice(0, color.length - 2));
        });

        polygon.balloon.events.add('close', () => {
            let coordinateCurrentPolygon = polygon.geometry.getCoordinates()[0];
            this.updateValueInputPolygon(polygon, {
                id: polygon.properties.get('id'),
                coordinates: coordinateCurrentPolygon,
                fill_color: polygon.options.get('fillColor'),
                stroke_color: polygon.options.get('strokeColor'),
                delivery_prices: polygon.properties.get('delivery_prices'),
                type: polygon.properties.get('type')
            });
        });
    }

    addEventChangeOptions(polygon) {
        polygon.options.events.add('change', (e) => {
            const options = e.get('target');
            const coordinateCurrentPolygon = polygon.geometry.getCoordinates()[0];
            this.updateValueInputPolygon(polygon, {
                id: polygon.properties.get('id'),
                coordinates: coordinateCurrentPolygon,
                fill_color: options.get('fillColor'),
                stroke_color: options.get('strokeColor'),
                delivery_prices: polygon.properties.get('delivery_prices'),
                type: polygon.properties.get('type')
            });
        });
    }

    addEventCheckIntersect(polygon) {
        polygon.events.add('geometrychange', (e) => {
            const element = e.get('target');
            const coordinateCurrentPolygon = element.geometry.getCoordinates()[0];

            this._map.geoObjects.each((item) => {
                if (item !== element) {
                    const itemCoordinates = item.geometry.getCoordinates()[0];

                    for (let i = 1; i < coordinateCurrentPolygon.length; i++) {
                        const [x1, y1] = coordinateCurrentPolygon[i - 1];
                        const [x2, y2] = coordinateCurrentPolygon[i];

                        for (let j = 1; j < itemCoordinates.length; j++) {
                            const [x3, y3] = itemCoordinates[j - 1];
                            const [x4, y4] = itemCoordinates[j];

                            if (this.lineSegmentsIntersect(x1, y1, x2, y2, x3, y3, x4, y4)) {
                                element.geometry.setCoordinates(e.get('oldCoordinates'));
                            }
                        }
                    }
                }
            });

            this.updateValueInputPolygon(polygon, {
                id: polygon.properties.get('id'),
                coordinates: coordinateCurrentPolygon,
                fill_color: element.options.get('fillColor'),
                stroke_color: element.options.get('strokeColor'),
                delivery_prices: element.properties.get('delivery_prices'),
                type: polygon.properties.get('type')
            });
        });
    }

    updateValueInputPolygon (polygon, value = {}) {
        const index = this._map.geoObjects.indexOf(polygon)
        if (index < 0) {
            return;
        }
        let polygonInputs = this.polygonsTarget.getElementsByTagName('input')
        polygonInputs = [...polygonInputs];

        let input = polygonInputs.find((element) => {
            return parseInt(element.dataset.index, 10) === index
        })

        if (!input) {
            input = document.createElement('input')
            input.type = 'text';
            input.dataset.index = index
            input.hidden = true
            input.name = this.data.get('field-name') + '[' + index + ']'
            this.polygonsTarget.appendChild(input);
        }

        input.value = JSON.stringify(value)
    }

    initPolygons() {
        if (this._value) {
            this._value.forEach((element) => {
                let polygon = this.addPolygon([element.coordinates], {
                        id: element.id,
                        store: this._store,
                        delivery_prices: element.delivery_prices,
                        types: this._types,
                        type: element.type
                    },
                    {
                        drawing: true,
                        draggable: false,
                        fillColor: element.fill_color ?? undefined,
                        strokeColor: element.stroke_color ?? undefined,
                    }
                )

                polygon.editor.startEditing()
                this.updateValueInputPolygon(polygon, {
                    id:  element.id,
                    coordinates: element.coordinates,
                    fill_color: element.fill_color,
                    stroke_color: element.stroke_color,
                    delivery_prices: element.delivery_prices,
                    type: element.type
                })
            })
        }

        let otherPolygons = this.data.get('other-polygons')
        if (otherPolygons) {
            otherPolygons = JSON.parse(otherPolygons)
            otherPolygons.forEach((element) => {
                let polygon = this.addPolygon([element.coordinates], {
                        balloonContentHeader: element.store.title,
                        balloonContentBody: element.store.address
                    }
                )
                this._map.geoObjects.add(polygon)
            })
        }

        if (this._map.geoObjects.getBounds()) {
            this._map.setBounds(this._map.geoObjects.getBounds());
        }
    }

    lineSegmentsIntersect(x1, y1, x2, y2, x3, y3, x4, y4) {
        const a_dx = x2 - x1;
        const a_dy = y2 - y1;
        const b_dx = x4 - x3;
        const b_dy = y4 - y3;
        let s = (-a_dy * (x1 - x3) + a_dx * (y1 - y3)) / (-b_dx * a_dy + a_dx * b_dy);
        let t = (+b_dx * (y1 - y3) - b_dy * (x1 - x3)) / (-b_dx * a_dy + a_dx * b_dy);

        s = s.toFixed(6)
        t = t.toFixed(6)
        return (s > 0 && s < 1 && t > 0 && t < 1) ? [x1 + t * a_dx, y1 + t * a_dy] : false;
    }
}
