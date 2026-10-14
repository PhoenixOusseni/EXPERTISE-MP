<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $subject ?? 'E-Formation' }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f6fb;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6fb;padding:32px 16px;">
  <tr>
    <td align="center">
      <table width="100%" cellpadding="0" cellspacing="0" style="max-width:580px;">

        {{-- Header --}}
        <tr>
          <td style="background:#1B4FD8;border-radius:12px 12px 0 0;padding:28px 36px;text-align:center;">
            <span style="color:#ffffff;font-size:22px;font-weight:700;letter-spacing:1px;">E-FORMATION</span>
            <p style="color:rgba(255,255,255,0.75);font-size:12px;margin:4px 0 0;">Expertise Marchés Publics</p>
          </td>
        </tr>

        {{-- Body --}}
        <tr>
          <td style="background:#ffffff;padding:36px 36px 28px;border-left:1px solid #e5e9f2;border-right:1px solid #e5e9f2;">
            @yield('content')
          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td style="background:#f0f3fa;border-radius:0 0 12px 12px;border:1px solid #e5e9f2;border-top:none;
                     padding:20px 36px;text-align:center;">
            <p style="color:#94a3b8;font-size:11px;margin:0;">
              © {{ date('Y') }} E-Formation — Expertise Marchés Publics<br>
              +226 78547805 · +226 76535063
            </p>
            <p style="color:#cbd5e1;font-size:10px;margin:8px 0 0;">
              Cet e-mail a été envoyé automatiquement, merci de ne pas y répondre.
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>
