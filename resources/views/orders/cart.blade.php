<div class="col-4">
  @component('components.card')
    @slot('header')
      Cart
    @endslot
    @slot('body')
        
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th>Qty</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            
            <tr v-for="(row, index) in shoppingCart">
              <td>@{{row.name}} (@{{ row.code }})</td>
              <td>@{{row.price}}</td>
              <td>@{{row.qty}}</td>
              <td>
                <button @click.prevent="removeCart(index)" class = "btn btn-danger btn-sm">
                  
                  <i class="fa fa-trash"></i>
                </button>
              </td>
            </tr>
            
          </tbody> 
        </table>
    @endslot
    @slot('footer')
      <div class="card-footer text-muted">
        @if(url()->current() == route('order.transaksi'))
          <a href="{{ route('order.checkout') }}" class="btn btn-info btn-sm float-right">
              Checkout
          </a>
        @else
          <a href="{{ route('order.transaksi') }}"  class="btn btn-secondary btn-sm float-right">
            Back
          </a>
        @endif
      </div>
    @endslot
  @endcomponent
</div>