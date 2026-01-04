export default class extends window.Controller {
    connect() {
        this.addListenerForFieldTitle()
    }

    addListenerForFieldTitle() {
        document.querySelector('.autocomplete .autocomplete-list')
            .addEventListener('click', event => {
                let item = event.target

                if (item.parentElement.tagName === 'LI') {
                    item = item.parentElement
                }

                if (item.tagName === 'LI') {
                    this.selectCity(item.dataset)

                    event.currentTarget.classList.remove('show')
                }
            })
    }

    selectCity(data) {
        this.setCityTitle(data.value)
        this.setFiasId(data.fias_id)
        this.setCoords(data.latitude, data.longitude)
        this.setIsSettlement(data.is_settlement)
    }

    setCityTitle(title) {
        const input = document.querySelector(`[name="${this.data.get('title-name')}"]`)

        input.value = title
    }

    setFiasId(fiasId) {
        const input = document.querySelector(`[name="${this.data.get('fias-id-name')}"]`)

        input.value = fiasId
    }

    setIsSettlement(isSettlement) {
        const input = document.querySelector(`[type="checkbox"][name="${this.data.get('is-settlement-name')}"]`)

        input.checked = isSettlement === 'true'
    }

    setCoords(lat, lon) {
        const latInput = document.querySelector(`[name="${this.data.get('latitude-name')}"]`)
        const lonInput = document.querySelector(`[name="${this.data.get('longitude-name')}"]`)

        latInput.value = lat
        lonInput.value = lon
    }
}
