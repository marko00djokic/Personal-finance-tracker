<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Izveštaj transakcija</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; line-height: 1.5; }
        .header { background-color: #4f46e5; color: white; padding: 20px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header p { font-size: 11px; color: #c7d2fe; margin-top: 4px; }
        .summary { display: flex; gap: 12px; margin: 0 24px 20px 24px; }
        .summary-card { flex: 1; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; }
        .summary-card .label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; }
        .summary-card .value { font-size: 15px; font-weight: bold; margin-top: 4px; }
        .income-val { color: #16a34a; }
        .expense-val { color: #dc2626; }
        .net-positive { color: #16a34a; }
        .net-negative { color: #dc2626; }
        table { width: calc(100% - 48px); margin: 0 24px; border-collapse: collapse; }
        thead tr { background-color: #f3f4f6; }
        thead th { padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; color: #6b7280; letter-spacing: 0.04em; border-bottom: 2px solid #e5e7eb; }
        thead th.right { text-align: right; }
        tbody tr { border-bottom: 1px solid #f3f4f6; }
        tbody tr:nth-child(even) { background-color: #f9fafb; }
        tbody td { padding: 7px 10px; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 9999px; font-size: 10px; font-weight: 600; }
        .badge-income { background-color: #dcfce7; color: #15803d; }
        .badge-expense { background-color: #fee2e2; color: #b91c1c; }
        .amount { text-align: right; font-weight: 600; }
        .amount-income { color: #16a34a; }
        .amount-expense { color: #dc2626; }
        .footer { margin: 24px 24px 0 24px; padding-top: 12px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #9ca3af; }
        .section-title { margin: 0 24px 10px 24px; font-size: 12px; font-weight: 600; color: #374151; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Izveštaj transakcija</h1>
        <p>
            {{ $user->name }}
            @if($from || $to)
                &nbsp;·&nbsp;
                Period: {{ $from ? \Carbon\Carbon::parse($from)->format('d.m.Y') : '—' }}
                –
                {{ $to ? \Carbon\Carbon::parse($to)->format('d.m.Y') : '—' }}
            @endif
            &nbsp;·&nbsp; Generisano: {{ now()->format('d.m.Y H:i') }}
        </p>
    </div>

    <div class="summary">
        <div class="summary-card">
            <div class="label">Prihodi</div>
            <div class="value income-val">{{ number_format($income, 2, ',', '.') }} RSD</div>
        </div>
        <div class="summary-card">
            <div class="label">Rashodi</div>
            <div class="value expense-val">{{ number_format($expenses, 2, ',', '.') }} RSD</div>
        </div>
        <div class="summary-card">
            <div class="label">Neto</div>
            <div class="value {{ $net >= 0 ? 'net-positive' : 'net-negative' }}">
                {{ $net >= 0 ? '+' : '' }}{{ number_format($net, 2, ',', '.') }} RSD
            </div>
        </div>
        <div class="summary-card">
            <div class="label">Broj transakcija</div>
            <div class="value">{{ $transactions->count() }}</div>
        </div>
    </div>

    <p class="section-title">Lista transakcija</p>

    @if($transactions->isEmpty())
        <p style="margin: 0 24px; color: #6b7280; font-size: 12px;">Nema transakcija za prikazivanje.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Tip</th>
                    <th>Kategorija</th>
                    <th>Opis</th>
                    <th class="right">Iznos (RSD)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $t)
                    <tr>
                        <td>{{ $t->transaction_date->format('d.m.Y') }}</td>
                        <td>
                            <span class="badge {{ $t->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                                {{ $t->type === 'income' ? 'Prihod' : 'Rashod' }}
                            </span>
                        </td>
                        <td>{{ $t->category?->name ?? '—' }}</td>
                        <td>{{ $t->description ?? '—' }}</td>
                        <td class="amount {{ $t->type === 'income' ? 'amount-income' : 'amount-expense' }}">
                            {{ $t->type === 'income' ? '+' : '-' }}{{ number_format($t->amount, 2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Personal Finance Tracker &nbsp;·&nbsp; {{ now()->format('d.m.Y H:i') }}
    </div>

</body>
</html>
