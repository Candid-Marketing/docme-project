<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stripe Subscription Plans</title>
  <script src="https://js.stripe.com/v3/"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/stripe.css') }}">

  <style>
    body {
      background: linear-gradient(90deg, #3abfdd, #3b68b2);
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .text-whites h1 {
      background: linear-gradient(90deg, rgb(92, 0, 131), rgb(241, 106, 214));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-size: 2.5rem;
      text-align: center;
    }

    @media (max-width: 768px) {
      .text-whites h1 { font-size: 1.8rem; }
    }

    @media (max-width: 576px) {
      .text-whites h1 { font-size: 1.5rem; }
      .modal-dialog { max-width: 90%; }
    }

    .pricing .pricing-card {
    height: 100%;
    padding: 2rem;
    background: var(--surface-color);
    border-radius: 1rem;
    transition: all 0.3s ease;
    position: relative;
  }

    .footer {
    color: var(--default-color);
    background-color: white;
    font-size: 14px;
    position: relative;
  }

  .footer .footer-top {
    padding-top: 50px;
  }

  .footer .footer-about .logo {
    line-height: 1;
    margin-bottom: 25px;
  }

  .footer .footer-about .logo img {
    max-height: 40px;
    margin-right: 6px;
  }

  .footer .footer-about .logo span {
    color: var(--heading-color);
    font-family: var(--heading-font);
    font-size: 26px;
    font-weight: 700;
    letter-spacing: 1px;
  }

  .footer .footer-about p {
    font-size: 14px;
    font-family: var(--heading-font);
  }

  .footer .social-links a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid color-mix(in srgb, var(--default-color), transparent 50%);
    font-size: 16px;
    color: color-mix(in srgb, var(--default-color), transparent 20%);
    margin-right: 10px;
    transition: 0.3s;
  }

  .footer .social-links a:hover {
    color: var(--accent-color);
    border-color: var(--accent-color);
  }

  .footer h4 {
    font-size: 16px;
    font-weight: bold;
    position: relative;
    padding-bottom: 12px;
  }

  .footer .footer-links {
    margin-bottom: 30px;
  }

  .footer .footer-links ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .footer .footer-links ul i {
    padding-right: 2px;
    font-size: 12px;
    line-height: 0;
  }

  .footer .footer-links ul li {
    padding: 10px 0;
    display: flex;
    align-items: center;
  }

  .footer .footer-links ul li:first-child {
    padding-top: 0;
  }

  .footer .footer-links ul a {
    color: color-mix(in srgb, var(--default-color), transparent 30%);
    display: inline-block;
    line-height: 1;
  }

  .footer .footer-links ul a:hover {
    color: var(--accent-color);
  }

  .footer .footer-contact p {
    margin-bottom: 5px;
  }

  .footer .copyright {
    padding: 25px 0;
    border-top: 1px solid color-mix(in srgb, var(--default-color), transparent 90%);
  }

  .footer .copyright p {
    margin-bottom: 0;
  }

  .footer .credits {
    margin-top: 8px;
    font-size: 13px;
  }

  </style>

  <link href="{{ asset('imgs/icon.png') }}" rel="icon">
  <link href="{{ asset('imgs/docme_logo.png') }}" rel="apple-touch-icon">
</head>

<body>
<section id="pricing" class="pricing section light-background">
  <div class=" pricing-bg container section-title text-center text-white" data-aos="fade-up">
      <h2>{!! nl2br(e($homepage->where('name', 'price_title')->first()->content ?? 'Choose Your Subscription Plan')) !!}</h2>
      <p>{!! nl2br(e($homepage->where('name', 'price_description')->first()->content ?? 'All plans include full user access. Guest access is always free.')) !!}</p>
  </div>
  @php
    $priceDayRaw = $homepage->where('name', 'price_day')->first()->content ?? null;
    $priceDay = is_numeric($priceDayRaw) ? floatval($priceDayRaw) : 9.00;
    $priceDayCents = intval($priceDay * 100);
@endphp

  <div class="container pricing-boxes  py-5" data-aos="fade-up" data-aos-delay="100">
    <div class="row justify-content-center g-4">
      <!-- Example Plan -->
      <div class="col-lg-4">
        <div class="pricing-card">
          <h3>Basic Plan</h3>
          <div class="price">
            <span class="currency">$</span>
            <span class="amount">{{ number_format($priceDay, 2) }}</span>
            <span class="period">/ day</span>
          </div>
          <p class="description">{!! nl2br(e($homepage->where('name', 'price_desc1')->first()->content ?? 'Ideal for single-day use or trial users who need quick access.')) !!}</p>
          <ul class="features-list">
            <li>
                <i class="bi bi-check-circle-fill"></i>
                {!! nl2br(e($homepage->where('name', 'include_state1')->first()->content ?? 'Duis aute irure dolor')) !!}
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i>
                {!! nl2br(e($homepage->where('name', 'include_state4')->first()->content ?? ' Excepteur sint occaecat')) !!}
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i>
                {!! nl2br(e($homepage->where('name', 'include_state7')->first()->content ?? ' Nemo enim ipsam voluptatem')) !!}
              </li>
              <li>
                  <i class="bi bi-check-circle-fill"></i>
                  {!! nl2br(e($homepage->where('name', 'include_state10')->first()->content ?? ' Nemo enim ipsam voluptatem')) !!}
              </li>
          </ul>

          <a href="https://buy.stripe.com/7sYcN54Znc214m00sAbII01" class="btn  btn-primary ">
                Buy Now
                <i class="bi bi-arrow-right"></i>
              </a>
        </div>
      </div>
      @php
        $priceMonthRaw = $homepage->where('name', 'price_month')->first()->content ?? null;
        $priceMonth = is_numeric($priceMonthRaw) ? floatval($priceMonthRaw) : 49.00;
        $priceMonthCents = intval($priceMonth * 100);
    @endphp
      <div class="col-lg-4">
        <div class="pricing-card popular">
          <div class="popular-badge">Most Popular</div>
          <h3>Standard Plan</h3>
          <div class="price">
            <span class="currency">$</span>
            <span class="amount">{{ number_format($priceMonth, 2) }}</span>
            <span class="period">/ month</span>
          </div>
          <p class="description">{!! nl2br(e($homepage->where('name', 'price_desc2')->first()->content ?? 'For growing businesses needing continuous access.')) !!}</p>
          <ul class="features-list">
            <li>
                <i class="bi bi-check-circle-fill"></i>
                {!! nl2br(e($homepage->where('name', 'include_state2')->first()->content ?? 'Lorem ipsum dolor sit amet')) !!}
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i>
                {!! nl2br(e($homepage->where('name', 'include_state5')->first()->content ?? 'Consectetur adipiscing elit')) !!}
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i>
                {!! nl2br(e($homepage->where('name', 'include_state8')->first()->content ?? 'Sed do eiusmod tempor')) !!}
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i>
                {!! nl2br(e($homepage->where('name', 'include_state11')->first()->content ?? ' Ut labore et dolore magna')) !!}
              </li>
          </ul>
             <a href="https://buy.stripe.com/dRm7sL4Znc21bOs5MUbII03" class="btn  btn-light ">
                Buy Now
                <i class="bi bi-arrow-right"></i>
              </a>

        </div>
      </div>
      @php
        $priceYearRaw = $homepage->where('name', 'price_year')->first()->content ?? null;
        $priceYear = is_numeric($priceYearRaw) ? floatval($priceYearRaw) : 490.00;
        $priceYearCents = intval($priceYear * 100);
    @endphp
      <div class="col-lg-4">
        <div class="pricing-card">
          <h3>Premium Plan</h3>
          <div class="price">
            <span class="currency">$</span>
            <span class="amount">{{ number_format($priceYear, 2) }}</span>
            <span class="period">/ month</span>
          </div>
          <p class="description"> {!! nl2br(e($homepage->where('name', 'price_desc3')->first()->content ?? 'For teams managing heavy usage and multiple users.')) !!}</p>
          <ul class="features-list">
            <li>
                <i class="bi bi-check-circle-fill"></i>
                {!! nl2br(e($homepage->where('name', 'include_state3')->first()->content ?? '  Saepe eveniet ut et voluptates ')) !!}
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i>
                {!! nl2br(e($homepage->where('name', 'include_state6')->first()->content ?? 'Nam libero tempore soluta')) !!}
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i>
                {!! nl2br(e($homepage->where('name', 'include_state9')->first()->content ?? ' Cumque nihil impedit quo ')) !!}
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i>
                {!! nl2br(e($homepage->where('name', 'include_state12')->first()->content ?? '  Maxime placeat facere possimus')) !!}
              </li>
          </ul>
            <a href=" https://buy.stripe.com/7sY5kD63r3vvbOsdfmbII00" class="btn  btn-primary ">
                Buy Now
                <i class="bi bi-arrow-right"></i>
              </a>

        </div>
      </div>
    </div>
  </div>
</section>

<!-- Stripe Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Complete Your Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="payment-form">
          <div class="mb-3">
            <label for="plan-details" class="form-label">Plan Details</label>
            <input type="text" id="plan-details" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label for="card-element" class="form-label">Enter your card details</label>
            <div id="card-element"></div>
            <small id="card-errors" class="text-danger mt-1"></small>
          </div>
          <button type="submit" class="btn w-100" style="background-color: #3b68b2; color: white;">Subscribe</button>
        </form>
      </div>
    </div>
  </div>
</div>

<footer id="footer" class="footer">

      <div class="container footer-top">
        <div class="row">
          <div class="col-lg-5 col-md-9 footer-about">
            <a href="index.html" class="logo d-flex align-items-center">
              <span class="sitename"><img class="sitename" src="{{ asset($homepage->where('name', 'footer_title')->first()->image_path ?? 'imgs/docme_new_logo.png') }}" alt="Favicon Image" /></span>
            </a>
            <div class="footer-contact pt-3">
              <p><strong>{!! nl2br(e($homepage->where('name', 'footer_add')->first()->content ?? 'A108 Adam Street
              New York, NY 535022')) !!}</strong></p>
              <p class="mt-3"><strong>Phone:</strong> <span>{!! nl2br(e($homepage->where('name', 'footer_phone')->first()->content ?? '+1 5589 55488 55')) !!}</span></p>
              <p><strong>Email:</strong> <span>{!! nl2br(e($homepage->where('name', 'footer_email')->first()->content ?? 'candidmarketing@gmail.com')) !!}</span></p>
            </div>

            <div class="mt-4"><strong>Follow us on</strong></div><br>
            <div class="social-links d-flex">
              <a href=""><i class="bi bi-twitter-x"></i></a>
              <a href=""><i class="bi bi-facebook"></i></a>
              <a href=""><i class="bi bi-instagram"></i></a>
              <a href=""><i class="bi bi-linkedin"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-md-3 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">About us</a></li>
              <li><a href="#">Services</a></li>
              <li><a href="#">Terms of service</a></li>
              <li><a href="#">Privacy policy</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="container post-footer">
        <div class="row">
          <div class="col-md-6">
          <div class="post-footer-copyright">
            <label>© 2024 Copyright. Software by Candid Marketing</label>
          </div>
          </div>
          <div class="col-md-6">
            <div class="post-footer-links">
              <a href="">Users term of Service</a>
              <a href="">Terms and Conditions</a>
              <a href="">Privacy Policy</a>
            </div>
          </div>
        </div>
      </div>
  </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
 const stripe = Stripe({!! json_encode($stripe->stripe_key) !!});
 const elements = stripe.elements({
  locale: 'en-AU'
});

const cardElement = elements.create('card', {
  hidePostalCode: true // This removes the field and avoids postcode validation entirely
});

cardElement.mount('#card-element');


  const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
  let selectedAmount = 0;

  document.querySelectorAll('.btn-subscribe').forEach(button => {
    button.addEventListener('click', function () {
      const planName = this.getAttribute('data-plan');
      selectedAmount = this.getAttribute('data-amount');
      document.getElementById('plan-details').value = `${planName} Plan - $${(selectedAmount / 100).toFixed(2)}`;
      paymentModal.show();
    });
  });

  $('#payment-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: "{{ route('payment.proceed') }}",
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      data: JSON.stringify({ amount: selectedAmount }),
      contentType: "application/json",
      success: function (response) {
        if (response.success) {
          stripe.confirmCardPayment(response.payment.client_secret, {
            payment_method: { card: cardElement }
          }).then(function (result) {
            if (!result.error) {
              $('#paymentModal').modal('hide');
              if (response.redirect_url) {
                window.location.href = response.redirect_url;
              } else {
                alert('Payment successful! Your invoice is ready.');
              }
            } else {
              $('#card-errors').text(result.error.message);
            }
          });
        } else {
          $('#card-errors').text(response.error || 'Something went wrong with the payment process.');
        }
      },
      error: function () {
        $('#card-errors').text('An error occurred. Please try again.');
      }
    });
  });
</script>
</body>
</html>
