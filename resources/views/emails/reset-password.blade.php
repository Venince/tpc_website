<!doctype html>
<html lang="en">
<body style="margin:0;padding:0;background:#fafafa;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#52525b;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#fafafa;width:100%;">
    <tr>
      <td align="center" style="padding:24px 12px;">
        <table role="presentation" width="570" cellspacing="0" cellpadding="0" style="width:570px;max-width:570px;">

          {{-- Header --}}
          <tr>
            <td align="center" style="padding:10px 10px 16px;">
                @if($hasLogo)
                    <img src="cid:{{ $logoCid }}"
                        alt="{{ $appName }} Logo"
                        style="height:80px;width:auto;display:block;margin:0 auto 8px;border:0;">
                @endif

                <div style="color:#008000;font-size:20px;font-weight:700;line-height:1.2;">
                    {{ $appName }}
                </div>
            </td>

          </tr>

          {{-- Card --}}
          <tr>
            <td style="background:#ffffff;border:1px solid #e4e4e7;border-radius:6px;box-shadow:0 1px 3px rgba(0,0,0,.10);padding:32px;">
              <h1 style="margin:0 0 14px;color:#008000;font-size:18px;font-weight:700;">Hello!</h1>

              <p style="margin:0 0 16px;font-size:16px;line-height:1.5;">
                You are receiving this email because we received a password reset request for your account.
              </p>

              <div style="text-align:center;margin:26px 0;">
                <a href="{{ $url }}" target="_blank" rel="noopener"
                   style="background:#008000;border-top:10px solid #008000;border-bottom:10px solid #008000;border-left:18px solid #008000;border-right:18px solid #008000;color:#ffffff;text-decoration:none;border-radius:4px;display:inline-block;font-weight:600;">
                  Reset Password
                </a>
              </div>

              <p style="margin:0 0 10px;font-size:16px;line-height:1.5;">
                This password reset link will expire in 60 minutes.
              </p>

              <p style="margin:0 0 18px;font-size:16px;line-height:1.5;">
                If you did not request a password reset, no further action is required.
              </p>

              <p style="margin:0;font-size:16px;line-height:1.5;">
                Regards,<br>{{ $signature }}
              </p>

              <div style="border-top:1px solid #e4e4e7;margin-top:25px;padding-top:20px;color:#71717a;font-size:14px;line-height:1.5;">
                If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br>
                <a href="{{ $url }}" style="color:#18181b;word-break:break-all;">{{ $url }}</a>
              </div>
            </td>
          </tr>

          {{-- Footer --}}
          <tr>
            <td align="center" style="padding:18px 10px;color:#a1a1aa;font-size:12px;">
              © {{ date('Y') }} {{ $appName }}. All rights reserved.
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
