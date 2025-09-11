<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Password Reset Code</title>
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
      overflow: hidden;
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

    .content-box {
      padding: 0 30px;
      text-align: left;
      font-size: 16px;
      color: #333;
    }

    .content-box p {
      margin: 15px 0;
    }

    .highlight-box {
        display: inline-block;
        background: linear-gradient(to right, #ED1D7E, #683695);
        color: #fff;
        padding: 10px 20px;
        border-radius: 50px;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        margin: 0 auto;
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

    .footer a {
      color: #683695;
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="card">
    <div class="header">
      <h2>Password Reset Request</h2>
      <p>Here's your verification code</p>
    </div>

    <img src="https://doc-me.com.au/imgs/docme_logo.png" alt="docME Logo" class="logo">

    <div class="content-box">
      <p>Hello,</p>
      <p>We received a request to reset your password for the email:</p>
      <p><strong>{{ $email }}</strong></p>

      <p>Your one-time verification code is:</p>
      <div style="text-align: center;">
        <div class="highlight-box">{{ $code }}</div>
      </div>

      <p style="margin-top: 20px;">This code will expire in 10 minutes. If you didn’t request a password reset, you can safely ignore this email.</p>
    </div>

    <div class="footer">
      <p>Need help? Visit <a href="https://doc-me.com.au/">our website</a> or contact support.</p>
    </div>
  </div>
</body>
</html>
