<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>One Time Password</title>
  <style>
    body {
      background-color: #e0e0e0;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 30px;
    }

    .card {
      max-width: 480px;
      margin: 0 auto;
      background-color: #ffffff;
      border-radius: 20px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
      overflow: visible;
      padding-bottom: 30px;
      text-align: center;
    }

    .header {
      background: linear-gradient(to right, #3ABFDD, #3B68B2);
      color: #ffffff;
      padding: 30px 20px 15px;
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
    }

    .header h2 {
      margin: 0;
      font-size: 24px;
    }

    .header p {
      margin: 8px 0 0;
      font-size: 14px;
      font-weight: normal;
    }

    .logo {
      margin: 25px auto 10px;
      width: 120px;
    }

    .message-box {
      background-color: #f0faff;
      margin: 20px 30px;
      padding: 20px;
      text-align: left;
      font-size: 16px;
      border-radius: 10px;
      color: #333;
    }

    .otp-code {
      display: inline-block;
      padding: 12px 30px;
      margin-top: 25px;
      background: linear-gradient(to right, #ED1D7E, #683695);
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 50px;
      cursor: default;
    }

    .note {
      font-size: 13px;
      color: #777;
      margin-top: 10px;
    }

    .footer {
      background-color: #eee;
      padding: 15px;
      font-size: 13px;
      color: #777;
      border-top: 1px solid #ddd;
      margin-top: 30px;
      border-bottom-left-radius: 20px;
      border-bottom-right-radius: 20px;
    }
  </style>
</head>
<body>

  <div class="card">
    <div class="header">
      <h2>One Time Password</h2>
      <p>Expect prompt, professional responses from our dedicated support team.</p>
    </div>

    <img src="https://doc-me.com.au/imgs/docme_logo.png" alt="docME Logo" class="logo">

    <div class="message-box">
      <strong>Message:</strong><br>
      Please use the OTP below to verify your account. The code is valid for 5 minutes.
    </div>

    <div class="otp-code" style="color: #ffffff; text-decoration: none;" >{{ $otp }}</div>
    <div class="note">This is a one-time code by docME</div>

    <div class="footer">
      &copy; {{ date('Y') }} Doc Me. All rights reserved.
    </div>
  </div>

</body>
</html>
