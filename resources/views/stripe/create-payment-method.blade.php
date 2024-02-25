<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Test Payment Method</div>

                <div class="card-body">
                    <form id="payment-form">
                        <div class="form-group">
                            <label for="card-element">Card Details</label>
                            <div id="card-element"><!-- Stripe Elements Placeholder --></div>
                        </div>
                        <div id="card-errors" role="alert"></div>
                        <div class="form-group">
                            <label for="name">Name on Card</label>
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="address">Billing Address</label>
                            <input type="text" id="address" name="address" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control">
                        </div>
                        <button id="submit" class="btn btn-primary">Save Payment Method</button>
                    </form>
                    <div id="payment-result"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');

    var form = document.getElementById('payment-form');
    var submitButton = document.getElementById('submit');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        submitButton.disabled = true;

        stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
            billing_details: {
                name: document.getElementById('name').value,
                address: document.getElementById('address').value,
                email: document.getElementById('email').value
            }
        }).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                submitButton.disabled = false;
            } else {
                // fetch('/single-charge/create', {
                //         method: 'POST',
                //         headers: {
                //             'Content-Type': 'application/json',
                //             'X-CSRF-TOKEN': "{{ csrf_token() }}"
                //         },
                //         body: JSON.stringify({
                //             paymentMethodId: result.paymentMethod.id,
                //             customerId: "cus_PcsA5zBlwf5ghF",
                //             amount: 400,
                //             paymentDescription: "Student Sponsership",
                //             // You can add additional data here if needed
                //         })
                //     })
                //     .then(response => response.json())
                //     .then(data => {
                //         document.getElementById('payment-result').innerHTML =
                //             'Payment Method Saved: ' + JSON.stringify(data);
                //     })
                //     .catch(error => {
                    //         console.error('Error:', error);
                    //     });
                document.getElementById('payment-result').innerHTML =
                    'Payment Method Saved: ' + result.paymentMethod.id;
            }
        });
    });
</script>
