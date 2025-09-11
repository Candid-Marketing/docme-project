<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Welcome Email</title>
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
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        margin: 0 auto;             /* Centre using margin */
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
      <h2>Welcome, {{ $user['first_name'] }} {{ $user['last_name'] }}</h2>
      <p>We’re excited to have you on board.</p>
    </div>

    <img src="https://doc-me.com.au/imgs/docme_logo.png" alt="docME Logo" class="logo">

    <div class="content-box">
      <p>Thank you for joining us at docME!</p>
      <p>Your registered email is: <strong>{{ $user['email'] }}</strong></p>
      <p>Your password is:</p>
      <div style="text-align: center;">
        <div class="highlight-box" style="color: #ffffff; text-decoration: none;" >{{ $plainPassword}}</div>
      </div>
      <p style="margin-top: 20px;">Please verify your email address to proceed to the website and change your password after logging in.</p>
    </div>

    <div class="footer">
      <p>Thank you for using our services.</p>
      <p>If you did not request this, please ignore this email.</p>
      <p><a href="https://doc-me.com.au/">Visit our website</a></p>
    </div>
  </div>
</body>
</html>
