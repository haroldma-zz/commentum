<script>
  var handler = StripeCheckout.configure({
    key: '{{ getenv('STRIPE_PUBLIC_KEY') }}',
    image: '/img/documentation/checkout/marketplace.png',
    token: function(token) {
      // Use the token to create the charge with a server-side script.
      // You can access the token ID with `token.id`

      var amountString = $('#stripeAmount').val().replace(/.+?([0-9\.]+).+/, "$1");
      var amount = parseFloat(amountString);

      $('<form action="/p/donate" method="POST">' + 
	    '<input type="hidden" name="_token" value="{{ csrf_token() }}">' + 
	    '<input type="hidden" name="stripeToken" value="' + token.id + '">' +
	    '<input type="hidden" name="stripeAmount" value="' + amount + '">' + 
	    '</form>').submit();
    }
  });

  $('#stripeDonate').click(function(e) {
    // Open Checkout with further options

    var amountString = $('#stripeAmount').val().replace(/.+?([0-9\.]+).+/, "$1");
    var amount = ~~(parseFloat(amountString) * 100);

    handler.open({
      name: 'Commentum',
      description: 'Donation',
      amount: amount
    });
    e.preventDefault();
  });

  // Close Checkout on page navigation
  $(window).on('popstate', function() {
    handler.close();
  });
</script>
