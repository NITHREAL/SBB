export default class extends window.Controller {
    static targets = [ "datalist" ]

    _delay = 300
    _minLength = 3
    _cancelSource = false
    _timeout

    setCancelSource (val) {
        this._cancelSource = val
    }

    setTimeout (val) {
        this._timeout = val
    }

    stopRequest () {
        if (this._cancelSource) {
            this._cancelSource.cancel()
            this._cancelSource = undefined
        }

        if (this._timeout) {
            clearTimeout(this._timeout)
            this._timeout = undefined
        }
    }

    keydown(event) {
        this.stopRequest()

        if (!event.code) {
            return
        }

        let timeout = setTimeout(() => {
            const { value } = event.target

            if (value.length >= this._minLength) {
                this.loadOptions(value)
            }
        }, this._delay)

        this.setTimeout(timeout)
    }

    async loadOptions(value) {
        const cancelSource = window.axios.CancelToken.source()

        this.setCancelSource(cancelSource)

        let url = this.dadataRoute
        let config = {
            params: {
                query: value
            }
        }

        if (cancelSource) {
            config.cancelToken = cancelSource.token
        }

        try {
            const result = await window.axios.get(url, config)

            if (result.status === 200 && result.data) {
                let list = document.querySelector('.autocomplete .autocomplete-list')
                list.innerHTML = ''

                result.data.forEach(element => {
                    let item = this.createAutocompleteItem(element)

                    list.appendChild(item)
                })

                list.classList.add('show')
            }
        } catch (e) {
            console.error(e)
        }
    }

    createAutocompleteItem(element) {
        var item      = document.createElement('li')
        var itemValue = document.createElement('div')
        var itemLabel = document.createElement('div');

        item.dataset.value         = element.title
        item.dataset.fias_id       = element.fias_id
        item.dataset.latitude      = element.latitude
        item.dataset.longitude     = element.longitude
        item.dataset.is_settlement = !!element.is_settlement

        itemValue.classList.add('autocomplete-label')
        itemLabel.classList.add('autocomplete-sublabel')

        itemValue.innerHTML = element.type + '. ' + element.title
        itemLabel.innerHTML = element.region

        item.appendChild(itemValue)
        item.appendChild(itemLabel)

        return item
    }

    get dadataRoute()
    {
        return this.data.get('dadata-route')
    }
}
