import Vue from 'vue'

export default class extends window.Controller {
    connect() {
        const app = new Vue({
            // el: this.element,
            // el: '#el',
    //         // template: '#filters-fieled',// this.element,
            data: {
                name: '',
                message: 'Hello, Vue.js!'
            }
        })
        app.mount(this.element);
    //     // Save the Vue instance to a property for later use
        this.app = app;
    //
        console.log( this.element, this.app);
    //
    }

    static get targets() {
        return [
            "name",
            "output"
        ]
    }

    greet() {
        this.outputTarget.textContent =
            `Hello, ${this.nameTarget.value}!`
    }

    disconnect() {
        // Destroy the Vue instance when the controller is disconnected
        this.app.$destroy()
    }
}
