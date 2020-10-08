const app = new Vue({
    el: '.pos-index',
    data: {
        items: [],
        total: 0,
        saleId: '',
    },
    mounted() {
        this.orderId = document.querySelector('#pay-order_id').value;
        this.load();
    },
    methods: {
        pushItem() {
        },
        popItem(index) {
        },
        load() {
        },
    }
});

Vue.component('payment_items', {
    props: ['id', 'index', 'name', 'installments', 'value'],
    template: '\
    <tr>\
        <td>{{ index+1 }}</td>\
        <td>{{ name }}</td>\
        <td>{{ unit_price }}</td>\
        <td>{{ amount }}</td>\
        <td>\
            <a href="#" class="text-muted">\
                <i class="fas fa-trash"></i>\
            </a>\
        </td>\
    </tr>\
    ',
})

function showToast(title, message = '') {
    $(document).Toasts('create', {
        title: title,
        body: message,
        autohide: true,
        delay: 5000,
        class: ['bg-warning', 'fix-toast']
    });
}

$('form').on('submit', e => false);