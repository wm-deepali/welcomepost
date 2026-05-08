<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
</head>
<body>
    <form id="redirectForm" action="{{ route('free-subscription') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $id }}">
        <input type="hidden" name="wallet_remaining" value="{{ $wallet_remaining }}">
        <input type="hidden" name="cashfree" value="{{ $cashfree }}">
        <input type="hidden" name="payment_id" value="{{ $payment_id }}">
        <input type="hidden" name="total_subscription" value="{{ $total_subscription }}">
        <input type="hidden" name="total_wout_gst" value="{{ $total_wout_gst }}">
        @php
            Session::forget('total_wout_gst');
        @endphp
    </form>
    <script type="text/javascript">
        document.getElementById('redirectForm').submit();
    </script>
</body>
</html>