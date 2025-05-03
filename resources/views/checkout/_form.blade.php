@auth
    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        
        <a href="{{ route('addresses.create') }}">
            Agregar dirección
        </a>
            <input type="hidden" name="url" value="{{ url()->current() }}">
        <div class="mb-3">
            <label for="shipping_method_id">Método de envío</label>
            <select name="shipping_method_id" id="shipping_method_id" class="form-control" required>
                @foreach ($shippingMethods as $method)
                    <option value="{{ $method->id }}">{{ $method->name }} - ${{ number_format($method->price, 2) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="payment_method_id">Método de pago</label>
            <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                @foreach ($paymentMethods as $method)
                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Confirmar compra</button>
    </form>
@endauth
