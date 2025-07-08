<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Checkout com Cartão</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vindi.js -->
    <script src="https://cdn.vindi.com.br/vindi.js"></script>
</head>

<body class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Pagamento com Cartão de Crédito</h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form id="checkout-form" method="POST" action="{{ url('/payments/subscribe') }}">
                            @csrf
                            <input type="hidden" id="plan_code" name="plan_code" value="{{ $planCode }}">
                            <input type="hidden" id="customer_code" name="customer_code" value="{{ $customerCode }}">
                            <input type="hidden" id="payment_method" name="payment_method" value="credit_card">

                            <div class="mb-3">
                                <label>Nome no Cartão</label>
                                <input type="text" class="form-control" id="holder_name" required>
                            </div>
                            <div class="mb-3">
                                <label>Número do Cartão</label>
                                <input type="text" class="form-control" id="card_number" required>
                            </div>
                            <div class="mb-3">
                                <label>Validade (MM/AAAA)</label>
                                <input type="text" class="form-control" id="expiration" required>
                            </div>
                            <div class="mb-3">
                                <label>CVV</label>
                                <input type="text" class="form-control" id="cvv" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Pagar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const vindiPublicKey = "{{ config('services.vindi.public_key') }}";

        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const cardData = {
                holder_name: document.getElementById('holder_name').value,
                number: document.getElementById('card_number').value,
                expiration: document.getElementById('expiration').value,
                cvv: document.getElementById('cvv').value
            };

            Vindi.tokenizeCard(vindiPublicKey, cardData, function(response) {
                if (response.error) {
                    alert('Erro: ' + response.error.message);
                    return;
                }

                alert(cardData);

                //e.target.submit(); // envia o form com o token no lugar dos dados do cartão
            });
        });
    </script>
</body>

</html>
