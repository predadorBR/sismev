const app = new Vue({
    el: '.checkout-index',
    data: {
        items: [],
        total: 0,
        saleId: '',
    },
    mounted() {
        this.saleId = document.querySelector('#pay-sale_id').value;
        this.load();
    },
    methods: {
        pushPay() {
            const form = document.querySelector('form');
            const formData = new FormData(form);
            axios.post('/api/pay/create', formData)
                .then(({ data }) => {
                    this.items.push(data.pay);
                    this.total = data.total;
                    const $form = $('form');
                    $form.get(0).reset();
                })
                .catch(({ response }) => {
                    const { name, message } = response.data;
                    showToast(name, message);
                });
        },
        popItem(index) {
        },
        load() {
            axios.get('/api/sale/', { params: { id: this.saleId } })
                .then(({ data }) => {
                    this.items = data.pays;
                    this.total = data.total;
                })
                .catch(({ response }) => {
                    const { name, message } = response.data;
                    showToast(name, message);
                });
        },
    }
});

Vue.component('payment_items', {
    props: ['id', 'index', 'name', 'installments', 'value'],
    template: '\
    <tr>\
        <td>{{ index+1 }}</td>\
        <td>{{ name }}</td>\
        <td>{{ installments }}</td>\
        <td>{{ value }}</td>\
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