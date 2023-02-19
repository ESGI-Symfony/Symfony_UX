if (document.getElementById("stripe-card-element")) {
    const stripe = Stripe(process.env.STRIPE_KEY);
    const elements = stripe.elements({locale: navigator.language ?? 'en'});
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#EFEEF6',
                '::placeholder': {
                    color: '#CCD5E6'
                }
            },
            invalid: {
                color: '#F59879',
                iconColor: '#F59879'
            }
        }
    });
    cardElement.mount('#stripe-card-element');

    document.getElementById("pay-btn").addEventListener("click", function (event) {
        createToken();
    })

    function createToken() {
        document.getElementById("pay-btn").disabled = true;
        stripe.createToken(cardElement).then(function (result) {
            if (typeof result.error != 'undefined') {
                document.getElementById("pay-btn").disabled = false;
                document.getElementById("error-stripe-message").innerText = result.error.message;
            }

            // creating token success
            if (typeof result.token != 'undefined') {
                document.getElementById("stripe-token-id").value = result.token.id;
                document.getElementById('checkout-form').submit();
            }
        });
    }
}


