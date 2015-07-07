<script>
  var handler = StripeCheckout.configure({
    key: '{{ getenv('STRIPE_PUBLIC_KEY') }}',
    image: '/img/documentation/checkout/marketplace.png',
    token: function(token) {
      // Use the token to create the charge with a server-side script.
      // You can access the token ID with `token.id`

      $('<form action="/p/donate" method="POST">' + 
	    '<input type="hidden" name="_token" value="{{ csrf_token() }}">' + 
	    '<input type="hidden" name="stripeToken" value="' + token.id + '">' +
	    '<input type="hidden" name="stripeAmount" value="' + parseFloat($('#stripeAmount').val()) + '">' + 
	    '</form>').submit();
    }
  });

  $('#stripeDonate').click(function(e) {
    // Open Checkout with further options
    handler.open({
      name: 'Commentum',
      description: 'Donation',
      amount: ~~(parseFloat($('#stripeAmount').val()) * 100)
    });
    e.preventDefault();
  });

  // Close Checkout on page navigation
  $(window).on('popstate', function() {
    handler.close();
  });
</script>
