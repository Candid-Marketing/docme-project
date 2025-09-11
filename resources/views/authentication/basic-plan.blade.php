<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doc Me </title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="{{ asset('imgs/icon.png') }}" rel="icon">
  <link href="{{ asset('imgs/docme_logo.png') }}" rel="apple-touch-icon">
  <script src="https://js.stripe.com/v3/"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .pricing-card {
      height: 100%;
      padding: 2rem;
      background: #ffffff;
      border-radius: 1rem;
      transition: all 0.3s ease;
      position: relative;
    }
    .pricing-card.popular {
      background: linear-gradient(90deg, #3abfdd, #3b68b2);
      color: #ffffff;
    }
    .pricing-card.popular h3,
    .pricing-card.popular h4,
    .pricing-card.popular .price .currency,
    .pricing-card.popular .price .amount,
    .pricing-card.popular .price .period,
    .pricing-card.popular .features-list li,
    .pricing-card.popular .features-list li i {
      color: #ffffff;
    }
    .pricing-card .popular-badge {
      position: absolute;
      top: -12px;
      left: 50%;
      transform: translateX(-50%);
      background: #ffffff;
      color: #0d83fd;
      padding: 0.5rem 1rem;
      border-radius: 2rem;
      font-size: 0.875rem;
      font-weight: 600;
      box-shadow: 0px -2px 10px rgba(0, 0, 0, 0.08);
    }
    .pricing-card h3 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
    .pricing-card .price {
      margin-bottom: 1.5rem;
    }
    .pricing-card .price .currency {
      font-size: 1.5rem;
      font-weight: 600;
      vertical-align: top;
      line-height: 1;
    }
    .pricing-card .price .amount {
      font-size: 3.5rem;
      font-weight: 700;
      line-height: 1;
    }
    .pricing-card .price .period {
      font-size: 1rem;
      color: rgba(33, 37, 41, 0.6);
    }
    .pricing-card .description {
      margin-bottom: 2rem;
      font-size: 0.975rem;
    }
    .pricing-card h4 {
      font-size: 1.125rem;
      margin-bottom: 1rem;
    }
    .pricing-card .features-list {
      list-style: none;
      padding: 0;
      margin: 0 0 2rem 0;
    }
    .pricing-card .features-list li {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
    }
    .pricing-card .features-list li i {
      color: #0d83fd;
      margin-right: 0.75rem;
      font-size: 1.25rem;
    }
    .pricing-card .btn {
      width: 100%;
      padding: 0.75rem 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      font-weight: 500;
      border-radius: 50px;
    }
    .pricing-card .btn.btn-primary {
      background: #0d83fd;
      border: none;
      color: #ffffff;
    }
    .pricing-card .btn.btn-primary:hover {
      background: rgba(13, 131, 253, 0.85);
    }

    .card-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        }

        .card-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        }

    .pricing-card h3
    {
        color: #0d83fd;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-box login">
   <form id="payment-form" method="POST" action="{{ route('register.pay') }}">
      <h3>Card Details</h3>
      <label for="card-number">Card Number</label>
      <div id="card-number" class="input-box mb-3"></div>
        <div class="card-row">
            <div class="card-col">
                <label for="card-expiry">Exp Date</label>
                <div id="card-expiry" class="input-box"></div>
            </div>
            <div class="card-col">
                <label for="card-cvc">CSV</label>
                <div id="card-cvc" class="input-box"></div>
            </div>
        </div>



      <div id="card-errors" role="alert" style="color:red;"></div>
      <input type="hidden" name="payment_method" id="payment-method">

      @csrf
      <div class="input-box">
        <input type="text" placeholder="First Name" name="fname" required value="{{ old('fname') }}">
        <i class='bx bxs-user-detail'></i>
      </div>
      <div class="input-box">
        <input type="text" placeholder="Last Name" name="lname" required value="{{ old('lname') }}">
        <i class='bx bxs-user-detail'></i>
      </div>
      <div class="input-box">
        <input type="email" placeholder="Email" name="email" required value="{{ old('email') }}">
        <i class='bx bx-envelope'></i>
      </div>
      <div class="input-box">
        <input type="password" placeholder="Password" name="pass" required>
        <i class='bx bx-lock'></i>
      </div>
      <div class="input-box">
        <input type="password" placeholder="Confirm Password" name="cpass" required>
        <i class='bx bx-lock'></i>
      </div>
    </form>
  </div>

  <div class="toggle-box">
    <div class="toggle-panel toggle-left">
      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="pricing-card">
          <h3>Basic Plan</h3>
          <div class="price">
            <span class="currency">$</span>
            <span class="amount">{!! nl2br(e($homepage->where('name', 'price_day')->first()->content ?? '1.00')) !!}</span>
            <span class="period">/ day</span>
          </div>
          <p class="description">{!! nl2br(e($homepage->where('name', 'price_desc1')->first()->content ?? 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium totam.')) !!}</p>
          <h4>Featured Included:</h4>
          <ul class="features-list">
            <li><i class="bi bi-check-circle-fill"></i> {!! nl2br(e($homepage->where('name', 'include_state1')->first()->content ?? 'Duis aute irure dolor')) !!}</li>
            <li><i class="bi bi-check-circle-fill"></i> {!! nl2br(e($homepage->where('name', 'include_state4')->first()->content ?? ' Excepteur sint occaecat')) !!}</li>
            <li><i class="bi bi-check-circle-fill"></i> {!! nl2br(e($homepage->where('name', 'include_state7')->first()->content ?? ' Nemo enim ipsam voluptatem')) !!}</li>
            <li><i class="bi bi-check-circle-fill"></i> {!! nl2br(e($homepage->where('name', 'include_state10')->first()->content ?? ' Nemo enim ipsam voluptatem')) !!}</li>
          </ul>

         <button
            type="button"
            id="submit-btn"
            class="btn w-100 mt-3"
            data-amount="{{ $homepage->where('name', 'price_day')->first()->content ?? '1.00' }}"
            style="background-color:linear-gradient(90deg, #ed1d7e , #3b68b2);">
            Pay and Register <i class="bi bi-arrow-right"></i>
        </button>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
const stripe = Stripe(@json($stripe->stripe_key));
const elements = stripe.elements({ locale: 'en-AU' });

const style = {
  base: {
    fontSize: '16px',
    color: '#212529',
    '::placeholder': { color: '#888' }
  },
  invalid: { color: '#ed1d7e' }
};

const cardNumber = elements.create('cardNumber', { style });
cardNumber.mount('#card-number');

const cardExpiry = elements.create('cardExpiry', { style });
cardExpiry.mount('#card-expiry');

const cardCvc = elements.create('cardCvc', { style });
cardCvc.mount('#card-cvc');

document.getElementById('submit-btn').addEventListener('click', async () => {
  const form = document.getElementById('payment-form');

  const fname = form.fname.value;
  const lname = form.lname.value;
  const email = form.email.value;
  const pass = form.pass.value;
  const cpass = form.cpass.value;
  const amount = document.getElementById('submit-btn').getAttribute('data-amount');

  // Create Stripe Payment Method
  const { paymentMethod, error } = await stripe.createPaymentMethod({
    type: 'card',
    card: cardNumber,
    billing_details: {
      name: fname + ' ' + lname,
      email: email
    }
  });

  if (error) {
    document.getElementById('card-errors').textContent = error.message;
    return;
  }

  // Clear any previous error
  document.getElementById('card-errors').textContent = '';

  // Send to Laravel via AJAX
  fetch("{{ route('register.pay') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": '{{ csrf_token() }}'
    },
    body: JSON.stringify({
      fname,
      lname,
      email,
      pass,
      cpass,
      amount,
      payment_method: paymentMethod.id // fixed this to use .id only
    })
  })
  .then(response => response.json())
  .then(data => {
  if (data.success) {
    Swal.fire({
      icon: 'success',
      title: 'Welcome!',
      text: 'Registration and payment successful!',
      confirmButtonText: 'Continue'
    }).then(() => {
      window.location.href = data.redirect_url; // ✅ Corrected!
    });
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Oops!',
      text: data.message || 'An error occurred'
    });
  }
})

  .catch(() => {
    Swal.fire({
      icon: 'error',
      title: 'Server Error',
      text: 'Please try again later.'
    });
  });
});

</script>

<script src="{{ asset('js/login.js') }}"></script>

</body>
</html>
