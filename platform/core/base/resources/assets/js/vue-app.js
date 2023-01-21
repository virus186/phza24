import Vue from 'vue';
import sanitizeHTML from 'sanitize-html';
import _ from 'lodash';

Vue.prototype.__ = key => {
    return _.get(window.trans, key, key);
};

Vue.prototype.$sanitize = sanitizeHTML;

class VueApp {
    constructor() {
        this.vue = Vue;
        this.bootingCallbacks = [];
    }

    booting(callback) {
        this.bootingCallbacks.push(callback);
    }

    boot() {
        for (const callback of this.bootingCallbacks) {
            callback(this.vue);
        }

        new this.vue({
            el: '#app'
        });
    }
}

window.vueApp = new VueApp();
