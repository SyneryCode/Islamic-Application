<!-- resources/views/reports/zakat.blade.php -->
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير الزكاة</title>
    <style>
        body { font-family: 'Arial', sans-serif; padding: 2cm; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: right; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { margin-top: 50px; font-size: 12px; color: #777; }
    </style>
</head>
<body>

<div class="header">
    <h1>تقرير الزكاة</h1>
    <p>للمستخدم: {{ $userId }}</p>
    <p>الفترة: {{ request('period') == 'monthly' ? 'شهرية' : 'سنوية' }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>النوع</th>
            <th>المبلغ</th>
            <th>قيمة النصاب</th>
            <th>قيمة الزكاة</th>
            <th>التاريخ الهجري</th>
            <th>المذهب</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $calc)
        <tr>
            <td>{{ $calc->type->name }}</td>
            <td>{{ number_format($calc->amount, 2) }}</td>
            <td>{{ number_format($calc->nisab_value, 2) }}</td>
            <td>{{ number_format($calc->zakat_value, 2) }}</td>
            <td>{{ $calc->hijri_date }}</td>
            <td>{{ $calc->fiqh_school }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    تم إنشاء هذا التقرير بواسطة نظام حساب الزكاة © {{ date('Y') }}
</div>

</body>
</html>