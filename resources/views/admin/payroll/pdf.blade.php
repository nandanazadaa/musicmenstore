<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Salary Slip - {{ $salarySlip->staff->nama }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
            /* Paksa portrait */
        }

        body {
    font-family: 'Helvetica', 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #121212 !important; /* Gunakan !important */
    color: #ffffff;
    line-height: 1.4;
    -webkit-print-color-adjust: exact; /* Paksa cetak warna background */
    print-color-adjust: exact;
}

        .container {
            padding: 30px 40px;
            /* Kurangi padding agar lebih ringkas */
            min-height: auto;
            /* Jangan paksa 100vh agar tidak narik halaman baru */
        }

        .header {
            margin-bottom: 20px;
            text-align: center;
        }

        .salary-table td,
        .salary-table th {
            padding: 8px 12px;
            /* Perkecil padding cell */
        }

        tr {
            page-break-inside: avoid;
        }

        .logo {
            width: 180px;
            /* Ukuran bisa disesuaikan */
            margin: 0 auto;
            display: block;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #C5A059;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-top: 15px;
            font-style: italic;
            /* Hapus border-bottom agar lebih bersih seperti di gambar */
            display: block;
        }

        .info-section {
            width: 100%;
            margin-bottom: 30px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px 0;
            font-size: 13px;
            vertical-align: top;
        }

        .label {
            width: 140px;
            color: #9a9a9a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .separator {
            width: 20px;
            text-align: center;
            color: #444;
        }

        .value {
            font-weight: bold;
            color: #fff;
        }

        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .salary-table th {
            background-color: #A6823C;
            color: #ffffff;
            padding: 12px 15px;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            border: none;
        }

        .salary-table td {
            background-color: #FFFFFF;
            color: #000000;
            padding: 12px 15px;
            font-size: 13px;
            font-weight: bold;
            border-bottom: 2px solid #f0f0f0;
        }

        .salary-table tr:last-child td {
            border-bottom: none;
        }

        /* Detail Breakdown Style */
        .sub-text {
            font-size: 10px;
            color: #666;
            font-weight: normal;
            display: block;
            margin-top: 2px;
        }

        .text-muted {
            color: #888;
            font-size: 11px;
            font-weight: normal;
        }

        .total-row {
            background-color: #A6823C !important;
            color: #ffffff !important;
        }

        .total-label {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .total-amount {
            font-size: 16px;
        }

        .deduction-section {
            margin-top: 15px;
        }

        .deduction-header {
            background-color: #3a2e00;
            color: #C5A059;
            padding: 8px 15px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: bold;
            text-align: right;
        }

        .deduction-body td {
            background-color: #fff8e1;
            color: #7c5700;
        }

        .take-home-pay {
            background-color: #1a5c35;
            color: #ffffff;
            padding: 15px 20px;
            margin-top: 5px;
            text-align: right;
        }

        .thp-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.8;
        }

        .thp-amount {
            font-size: 20px;
            font-weight: bold;
            display: block;
        }

        .bank-section {
            margin-top: 50px;
            border-top: 1px solid #333;
            pt-10;
        }

        .bank-title {
            color: #C5A059;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            display: block;
            font-weight: bold;
        }

        .footer {
            position: absolute;
            bottom: 40px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
            letter-spacing: 3px;
            color: #444;
            text-transform: uppercase;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="header">
            <div style="margin-bottom: 10px;">
                <img src="{{ public_path('images/logo2.png') }}" class="logo" alt="Logo">
            </div>
            <div class="title">
                SALARY SLIP {{ strtoupper(\Carbon\Carbon::parse($salarySlip->month . '-01')->translatedFormat('F Y')) }}
            </div>
        </div>

        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td class="label">Employee Name</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $salarySlip->staff->nama }}</td>
                </tr>
                <tr>
                    <td class="label">Employee ID</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $salarySlip->staff->id_employee ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Job Title</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $salarySlip->staff->jabatan ?? 'Staff Store' }}</td>
                </tr>
            </table>
        </div>

        <table class="salary-table">
            <thead>
                <tr>
                    <th class="text-left" width="60%">DESCRIPTION</th>
                    <th class="text-right" width="40%">AMOUNT (IDR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left">Basic Salary</td>
                    <td class="text-right">Rp {{ number_format($salarySlip->basic_salary, 0, ',', '.') }}</td>
                </tr>
                @if ($salarySlip->transport_food_allowance > 0)
                    <tr>
                        <td class="text-left">Transport & Food Allowance</td>
                        <td class="text-right">Rp
                            {{ number_format($salarySlip->transport_food_allowance, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if ($salarySlip->overtime_fee > 0)
                    <tr>
                        <td class="text-left">Overtime Fee</td>
                        <td class="text-right">Rp {{ number_format($salarySlip->overtime_fee, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if ($salarySlip->sales_fee > 0)
                    <tr>
                        <td class="text-left">Sales Fee <span class="sub-text">Incentive from unit sales &
                                products</span></td>
                        <td class="text-right">Rp {{ number_format($salarySlip->sales_fee, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if ($salarySlip->service_fee > 0)
                    <tr>
                        <td class="text-left">Service Fee <span class="sub-text">Handled service commission</span></td>
                        <td class="text-right">Rp {{ number_format($salarySlip->service_fee, 0, ',', '.') }}</td>
                    </tr>
                @endif

                {{-- Dynamic Others Fee --}}
                @if ($salarySlip->others_fee > 0)
                    @php $details = $salarySlip->others_fee_details ?? []; @endphp
                    @foreach ($details as $item)
                        <tr>
                            <td class="text-left">{{ $item['name'] }}</td>
                            <td class="text-right">Rp {{ number_format($item['amount'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endif

                @if ($salarySlip->performance_incentive > 0)
                    <tr>
                        <td class="text-left">
                            Performance Incentive
                            <span class="sub-text">Achieved: {{ $salarySlip->total_points ?? 0 }} points (x Rp
                                1.000)</span>
                        </td>
                        <td class="text-right">Rp
                            {{ number_format($salarySlip->performance_incentive ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        {{-- GROSS EARNINGS --}}
        <div class="total-row" style="padding: 12px 15px; font-weight: bold;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="left" class="total-label">Gross Earnings</td>
                    <td align="right" class="total-amount">Rp {{ number_format($salarySlip->total, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- DEDUCTIONS (CASH ADVANCE) --}}
        @if (($salarySlip->cash_advance ?? 0) > 0)
            <div class="deduction-section">
                <div class="deduction-header">Deductions / Potongan</div>
                <table class="salary-table">
                    <tbody class="deduction-body">
                        <tr>
                            <td class="text-left" width="60%">
                                Cash Advance (Kasbon)
                                @if ($salarySlip->cash_advance_notes)
                                    <span class="sub-text" style="color: #92400e;">Note:
                                        {{ $salarySlip->cash_advance_notes }}</span>
                                @endif
                            </td>
                            <td class="text-right" width="40%">(Rp
                                {{ number_format($salarySlip->cash_advance, 0, ',', '.') }})</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

        {{-- FINAL TAKE-HOME PAY --}}
        <div class="take-home-pay">
            <span class="thp-label">Total Take-Home Pay</span>
            <span class="thp-amount">Rp {{ number_format($salarySlip->take_home_pay, 0, ',', '.') }}</span>
        </div>

        {{-- BANK INFO DARI PROFIL OTOMATIS --}}
        <div class="bank-section">
            <span class="bank-title">Transfer Information</span>
            <table class="info-table">
                <tr>
                    <td class="label" style="width: 100px;">Bank</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $salarySlip->bank_name ?? 'NOT SET' }}</td>
                </tr>
                <tr>
                    <td class="label" style="width: 100px;">Account</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $salarySlip->account_name ?? $salarySlip->staff->nama }}</td>
                </tr>
                <tr>
                    <td class="label" style="width: 100px;">Account No</td>
                    <td class="separator">:</td>
                    <td class="value" style="font-family: monospace; font-size: 15px;">
                        {{ $salarySlip->bank_account ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">MUSICMEN STORE YOGYAKARTA • www.musicmenstore.com</div>
    </div>
</body>

</html>
