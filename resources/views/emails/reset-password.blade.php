<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - TirtaX</title>
</head>

<body style="margin: 0; padding: 0; font-family: 'Inter', Arial, sans-serif; background-color: #f0f4ff;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="background-color: #f0f4ff; padding: 40px 0;">
        <tr>
            <td align="center">

                <!-- Container Utama -->
                <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">

                    <!-- Header dengan Logo -->
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #0f1d4a 0%, #1e3a8a 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 800;">
                                Tirta<span style="color: #7dd3fc;">X</span>
                            </h1>
                            <p style="color: #bae6fd; margin: 10px 0 0 0; font-size: 14px;">Platform Ekspedisi Digital
                            </p>
                        </td>
                    </tr>

                    <!-- Icon Kunci -->
                    <tr>
                        <td style="padding: 40px 30px 20px 30px; text-align: center;">
                            <div
                                style="width: 80px; height: 80px; background-color: #e0f2fe; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#1e40af"
                                    stroke-width="2">
                                    <path
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                        </td>
                    </tr>

                    <!-- Judul -->
                    <tr>
                        <td style="padding: 0 30px 20px 30px; text-align: center;">
                            <h2 style="color: #0f1d4a; margin: 0; font-size: 24px; font-weight: 700;">Reset Password
                                Anda</h2>
                            <p style="color: #6b7280; margin: 10px 0 0 0; font-size: 15px; line-height: 1.6;">
                                Halo <strong style="color: #0f1d4a;">{{ $userName }}</strong>,
                            </p>
                            <p style="color: #6b7280; margin: 15px 0 0 0; font-size: 15px; line-height: 1.6;">
                                Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.
                            </p>
                        </td>
                    </tr>

                    <!-- Tombol Reset -->
                    <tr>
                        <td style="padding: 20px 30px; text-align: center;">
                            <a href="{{ $resetUrl }}"
                                style="display: inline-block; background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 10px; font-weight: 700; font-size: 16px; box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);">
                                🔑 Reset Password Sekarang
                            </a>
                        </td>
                    </tr>

                    <!-- Info Link -->
                    <tr>
                        <td style="padding: 10px 30px 20px 30px; text-align: center;">
                            <p style="color: #9ca3af; margin: 0; font-size: 13px;">
                                Atau copy link berikut ke browser Anda:
                            </p>
                            <p
                                style="color: #1e40af; margin: 10px 0 0 0; font-size: 12px; word-break: break-all; background-color: #f0f4ff; padding: 10px; border-radius: 8px; border: 1px solid #dbe4ff;">
                                {{ $resetUrl }}
                            </p>
                        </td>
                    </tr>

                    <!-- Warning Box -->
                    <tr>
                        <td style="padding: 0 30px 20px 30px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 15px;">
                                        <p style="color: #92400e; margin: 0; font-size: 13px; line-height: 1.6;">
                                            ⚠️ <strong>Link ini akan kedaluwarsa dalam 60 menit.</strong> Jika Anda
                                            tidak meminta reset password, abaikan email ini.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #0f1d4a; padding: 30px; text-align: center;">
                            <p style="color: #ffffff; margin: 0; font-size: 14px; font-weight: 600;">
                                Terima kasih,<br>
                                <span style="color: #7dd3fc;">Tim TirtaX</span>
                            </p>
                            <p style="color: #91a7ff; margin: 15px 0 0 0; font-size: 12px;">
                                📧 support@tirtax.com | 📞 0800-123-4567
                            </p>
                            <p style="color: #6b7280; margin: 15px 0 0 0; font-size: 11px;">
                                © 2026 TirtaX Logistik. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>