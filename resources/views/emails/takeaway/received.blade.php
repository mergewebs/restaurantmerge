<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Takeaway Order</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111; line-height: 1.5;">
    <h2 style="margin-bottom: 8px;">New Takeaway Order #{{ $order->id }}</h2>
    <p style="margin: 0 0 12px;">
        Customer: <strong>{{ $order->customer_name }}</strong>
        @if($order->customer_phone)
            ({{ $order->customer_phone }})
        @endif
    </p>
    <p style="margin: 0 0 16px;">
        Total: <strong>EUR {{ number_format((float) $order->total, 2) }}</strong>
    </p>

    <table cellpadding="6" cellspacing="0" border="1" style="border-collapse: collapse; border-color: #ddd; width: 100%;">
        <thead>
            <tr style="background: #f6f6f6;">
                <th align="left">Item</th>
                <th align="right">Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td align="right">{{ (int) $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
