import CreateOrder from './components/CreateOrderComponent';
import {BModal, VBModal} from 'bootstrap-vue';

vueApp.booting(vue => {
    vue.filter('formatPrice', value => {
        return parseFloat(value).toFixed(2);
    });

    vue.component('b-modal', BModal);
    vue.directive('b-modal', VBModal);

    vue.component('create-order', CreateOrder);
});
