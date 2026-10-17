<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0;padding:0;background:#edf1f2;font-family:Arial,Helvetica,sans-serif;color:#1d2a36;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#edf1f2;padding:24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:16px;">
                <tr>
                    <td style="padding:24px 28px 4px;font-size:20px;font-weight:bold;">
                        🐾 {{ config('patte.name') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:12px 28px 24px;font-size:16px;line-height:1.55;">
                        @yield('content')
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 28px 22px;border-top:1px solid #e1e7ea;font-size:13px;line-height:1.5;color:#546270;">
                        {{ config('patte.name') }} – {{ config('patte.area') }}<br>
                        {{ config('patte.phone') }} – {{ config('patte.email') }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
