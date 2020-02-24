import Vue from 'vue'
import axios from 'axios'
//import sweetalert library
import 'sweetalert2/dist/sweetalert2.min.css';
import VueSweetalert2 from 'vue-sweetalert2';

Vue.filter('currency', function (money) {
    return accounting.formatMoney(money, "Rp ", 2, ".", ",")
})

// use sweetalert
Vue.use(VueSweetalert2);

new Vue({
    el: '#dw',
    data: {
        product: {
            id: '',
            price: '',
            name: '',
            photo: ''
        },
        // menambahkan cart
        cart:{
            product_id: '',
            qty:1
        },
        // menampung list cart
        shoppingCart:[],
        submitCart: false,

        // customer
        customer: {
            email:''
        },
        formCustomer    : false,
        resultStatus    : false,
        submitForm      : false,
        errorMessage    : '',
        message         : '',
        messageEmail    : ''

    },
    watch: {
        //apabila nilai dari product > id berubah maka
        'product.id': function() {
            //mengecek jika nilai dari product > id ada
            if (this.product.id) {
                //maka akan menjalankan methods getProduct
                this.getProduct()
            }
        },

        'cutomer.email' : function(){
            this.formCustomer = false
            if(this.cutomer.name != ''){
                this.cutomer = {
                    name    : '',
                    phone   : '',
                    address : ''
                }
            }
        }

    },
    //menggunakan library select2 ketika file ini di-load
    mounted() {
        $('#product_id').select2({
            placeholder: "Select a product",
            width: '100%'
        }).on('change', () => {
            //apabila terjadi perubahan nilai yg dipilih maka nilai tersebut 
            //akan disimpan di dalam var product > id
            this.product.id = $('#product_id').val();
            this.cart.product_id = $('#product_id').val();
        });

        // memanggil method getCart() untuk me-load cookie cart
        this.getCart()
    },
    methods: {
        getProduct() {
            //fetch ke server menggunakan axios dengan mengirimkan parameter id
            //dengan url /api/product/{id}
            axios.get(`/api/product/${this.product.id}`)
            .then((response) => {
                //assign data yang diterima dari server ke var product
                this.product = response.data
            })
        },

        // menambahkan product yang di pilih ke cart
        addToCart(){
            
            this.submitCart = true;
            // kirim data ke server
            axios.post('/api/cart', this.cart)
            .then((response)=>{
                setTimeout(()=>{
                    // jika berhasil, data disimpan ke variabel shooingCart
                    this.shoppingCart = response.data

                    // mengosongkan variabel
                    this.cart.product_id = ''
                    this.cart.qty = 1
                    this.product = {
                        id: '',
                        price: '',
                        name: '',
                        photo: ''
                    }
                    // reset select2
                    $('#product_id').select2("val", "0");
                    this.submitCart = false

                }, 200);
            })
            .catch((error)=>{

            })
        },

        // mengambil list yang sudah disimpan
        getCart(){
            axios.get('api/cart')
            .then((response)=>{
                //data yang diterima disimpan ke dalam var shoppingCart
                this.shoppingCart = response.data
            })
        },

        // hapus cart
        removeCart(id){
            //menampilkan konfirmasi dengan sweetalert
            this.$swal({
                title: 'Are you sure?',
                text: `You won't be able to revert this!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, do it!',
                cancelButtonText: 'No, cancel!',
                showCloseButton: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            resolve()
                        }, 2000)
                    })
                },
                allowOutsideClick: () => !this.$swal.isLoading()
            }).then ((result) => {
                //apabila disetujui
                if (result.value) {
                    //kirim data ke server
                    axios.delete(`/api/cart/${id}`)
                    .then ((response) => {
                        //load cart yang baru
                        this.getCart();
                    })
                    .catch ((error) => {
                        console.log(error);
                    })
                }
            })
        },


        // cari customer
        searchCustomer(){
            axios.post('/api/customer/search', {
                email : this.customer.email
            })
            .then((response)=>{
                if(response.data.status == 'success'){
                    this.customer           = response.data.data
                    this.customer.email     = response.data.data[0].email
                    this.customer.name      = response.data.data[0].name
                    this.customer.address   = response.data.data[0].address
                    this.customer.phone     = response.data.data[0].phone
                    this.resultStatus       = true
                    this.formCustomer       = true
                    this.messageEmail       = ''
                }else if( response.data.status == 'emtpy' ){
                    this.customer.name      = ''
                    this.customer.address   = ''
                    this.customer.phone     = ''
                    this.resultStatus       = true
                    this.formCustomer       = false
                    this.messageEmail       = '* Customer not found.'
                }
            })
            .catch((error)=>{
                console.log(error)
            })
        },
         
        sendOrder(){
            this.orderMessage   = ''
            this.message        = ''
            this.messageEmail   = ''

            //jika data customer tidak kosong
            if(this.customer.email != '' && this.customer.name != '' && this.customer.phone != '' && this.customer.address != ''){
                this.$swal({
                    title   : 'Are you sure?',
                    text    : 'You cannot repeat this action !',
                    icon    : 'warning',
                    confirmButtonText   : 'Yes, do it.',
                    cancelButtonText    : 'No, cancel',
                    showCancelButton    : true,
                    showCloseButton     : true,
                    showLoaderOnConfirm : true,
                    preConfirm : ()=>{
                        return new Promise((resolve)=> {
                            setTimeout(()=>{
                                resolve()
                            }, 2000)
                        })
                    },
                    allowOutsideClick : () => !this.$swal.isLoading()
                })
                .then((result)=>{
                    if(result.value){
                        this.submitForm = true,

                        //mengirimkan data dengan uri /checkout
                        axios.post('/checkout', this.customer)
                        .then((response)=>{
                            setTimeout(()=>{
                                this.getCart();
                                this.message = response.data.message

                                this.customer = {
                                    email   : '',
                                    name    : '',
                                    phone   : '',
                                    address : ''
                                }
                                this.formCustomer   = false
                                this.submitForm     = false
                            }, 1000)
                        })
                        .catch((error)=>{
                            console.log(error)
                        })

                    }
                })
            } else {
                //jika form kosong, maka error message ditampilkan
                this.errorMessage = 'Form cannot empty!'
            }
        }

    }
})