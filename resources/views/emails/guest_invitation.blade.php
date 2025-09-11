<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>You've Been Invited to View a File</title>
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
    }

    .header {
      background: linear-gradient(to right, #3ABFDD, #3B68B2);
      color: #ffffff;
      padding: 30px 20px 15px;
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
      text-align: center;
    }

    .header h2 {
      margin: 0;
      font-size: 22px;
    }

    .header p {
      margin: 8px 0 0;
      font-size: 14px;
      font-weight: normal;
    }

    .content-box {
      padding: 30px;
      text-align: left;
      font-size: 16px;
      color: #333;
    }

    .content-box p {
      margin: 15px 0;
    }

    .message-block {
      background-color: #f0f4ff;
      padding: 15px;
      border-left: 4px solid #3B68B2;
      font-size: 15px;
      margin-bottom: 20px;
    }

    .access-button {
      display: inline-block;
      background: linear-gradient(to right, #ED1D7E, #683695);
      color: #ffffff;
      padding: 12px 30px;
      text-decoration: none;
      border-radius: 50px;
      font-size: 15px;
      font-weight: bold;
      margin-top: 20px;
    }

    .button-wrapper {
      text-align: center;
      margin-top: 30px;
      margin-bottom: 20px;
    }

    .footer {
      background-color: #eee;
      padding: 15px;
      font-size: 13px;
      color: #777;
      border-top: 1px solid #ddd;
      text-align: center;
      border-bottom-left-radius: 20px;
      border-bottom-right-radius: 20px;
    }
  </style>
</head>
<body>

  <div class="card">
    <div class="header">
      <h2>DocME</h2>
      <p>File Invitation Notification</p>
    </div>

    <div class="content-box">
      <p>Hello,</p>

      <p>You've been invited to access a file by <strong>{{ $invitation->inviter->first_name }} {{ $invitation->inviter->last_name }}</strong>.</p>

      @if($invitation->message)
        <div class="message-block">
          <strong>Message:</strong><br>
          {{ $invitation->message }}
        </div>
      @endif

      @if($invitation->available_from || $invitation->available_until)
        <p>
          <strong>Access Window:</strong><br>
          From: {{ $invitation->available_from ? \Carbon\Carbon::parse($invitation->available_from)->format('d M Y, h:i A') : 'Now' }}<br>
          Until: {{ $invitation->available_until ? \Carbon\Carbon::parse($invitation->available_until)->format('d M Y, h:i A') : 'No expiry set' }}
        </p>
      @endif

      <p>Please log in or register to view the file using your secure access.</p>

      <div class="button-wrapper">
        <a href="{{ url('/') }}" class="access-button" style="color: #ffffff; text-decoration: none;">Access Platform</a>
      </div>

      <p style="font-size: 13px; color: #999; text-align: center;">
        This email was sent by Doc Me on behalf of {{ $invitation->inviter->first_name }} {{ $invitation->inviter->last_name }}.
      </p>
    </div>

    <div class="footer">
      &copy; {{ date('Y') }} Doc Me. All rights reserved.
    </div>
  </div>

</body>
</html>
