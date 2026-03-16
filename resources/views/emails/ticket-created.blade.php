<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Anda Diterima</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f7fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f7fa; padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%); padding:30px 40px; text-align:center;">
                            <h1 style="color:#ffffff; margin:0; font-size:22px; font-weight:600;">
                                🎫 IT HELPDESK
                            </h1>
                            <p style="color:#a0c4e8; margin:8px 0 0; font-size:14px;">
                                Laporan Anda Berhasil Diterima
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:30px 40px;">
                            <p style="color:#2d3748; font-size:16px; line-height:1.6; margin:0 0 20px;">
                                Halo <strong>{{ $reporter->full_name }}</strong>,
                            </p>

                            <p style="color:#4a5568; font-size:14px; line-height:1.6; margin:0 0 24px;">
                                Terima kasih telah melaporkan kendala Anda. Laporan telah berhasil dicatat dalam sistem kami dan akan segera kami proses.
                            </p>

                            {{-- Ticket Info Card --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7fafc; border-radius:8px; border: 1px solid #e2e8f0; margin-bottom:24px;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <h3 style="color:#1e3a5f; margin:0 0 16px; font-size:15px; border-bottom: 2px solid #e2e8f0; padding-bottom:10px;">
                                            📋 Detail Laporan
                                        </h3>
                                        <table width="100%" cellspacing="0" cellpadding="6">
                                            <tr>
                                                <td style="color:#718096; font-size:13px; width:140px; vertical-align:top;">Nomor Ticket</td>
                                                <td style="color:#2d3748; font-size:13px; font-weight:700;">{{ $ticket->ticket_number }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#718096; font-size:13px; vertical-align:top;">Judul</td>
                                                <td style="color:#2d3748; font-size:13px;">{{ $ticket->title }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#718096; font-size:13px; vertical-align:top;">Kategori</td>
                                                <td style="color:#2d3748; font-size:13px;">{{ $ticket->category->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color:#718096; font-size:13px; vertical-align:top;">Prioritas</td>
                                                <td>
                                                    <span style="display:inline-block; padding:3px 10px; border-radius:12px; font-size:12px; font-weight:600;
                                                        @if($ticket->priority === 'Critical') background-color:#fed7d7; color:#c53030;
                                                        @elseif($ticket->priority === 'High') background-color:#feebc8; color:#c05621;
                                                        @elseif($ticket->priority === 'Medium') background-color:#fefcbf; color:#975a16;
                                                        @else background-color:#c6f6d5; color:#276749;
                                                        @endif">
                                                        {{ $ticket->priority }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color:#718096; font-size:13px; vertical-align:top;">Estimasi SLA</td>
                                                <td style="color:#2d3748; font-size:13px; font-weight:600;">
                                                    {{ $ticket->sla_deadline ? $ticket->sla_deadline->format('d M Y, H:i') . ' WIB' : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color:#718096; font-size:13px; vertical-align:top;">Status</td>
                                                <td>
                                                    <span style="display:inline-block; padding:3px 10px; border-radius:12px; font-size:12px; font-weight:600; background-color:#bee3f8; color:#2b6cb0;">
                                                        {{ $ticket->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- Track Button --}}
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding:8px 0 24px;">
                                        <a href="{{ $trackingUrl }}" style="display:inline-block; background:linear-gradient(135deg, #2c5282, #3182ce); color:#ffffff; text-decoration:none; padding:14px 36px; border-radius:8px; font-size:14px; font-weight:600; letter-spacing:0.5px;">
                                            🔍 Lacak Status Ticket
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="color:#a0aec0; font-size:12px; line-height:1.5; text-align:center; margin:0;">
                                Link tracking berlaku selama 7 hari. Anda juga bisa melacak ticket melalui halaman
                                <a href="{{ route('helpdesk.track') }}" style="color:#3182ce;">Lacak Tiket</a>.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#f7fafc; padding:20px 40px; text-align:center; border-top:1px solid #e2e8f0;">
                            <p style="color:#a0aec0; font-size:12px; margin:0;">
                                &copy; {{ date('Y') }} IT Helpdesk — Sistem Laporan Kendala IT
                            </p>
                            <p style="color:#cbd5e0; font-size:11px; margin:4px 0 0;">
                                Email ini dikirim otomatis, mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
