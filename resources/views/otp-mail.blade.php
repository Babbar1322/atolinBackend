<!DOCTYPE html>
<html lang="en" style="margin: 0; padding: 0; border: 0;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>

</head>
<body style="border: 0; background-color: #dddddd; font-size: 16px; max-width: 700px; margin: 0 auto; padding: 2%; color: #000000; font-family: 'Open Sans', sans-serif;">
    <div class="container" style="margin: 0; padding: 0; border: 0; background-color: #ffffff;">
        <div class="logo" style="margin: 0; border: 0; padding: 1%; text-align: center;">
            <img src="{{asset()}}" style="margin: 0; padding: 0; border: 0; max-width: 120px;">
        </div>

        <div class="one-col" style="margin: 0; border: 0; padding: 20px 10px 40px; text-align: center;">
            <h1 style="margin: 0; padding: 0; border: 0; padding-bottom: 15px; letter-spacing: 1px;">Welcome to Priority 🎉</h1>
            <p style="margin: 0; padding: 0; border: 0; line-height: 28px; padding-bottom: 25px;">Your recent payment has been successfully processed, and we're excited to have you on board</p>
            <p style="margin: 0; padding: 0; border: 0; line-height: 28px; padding-bottom: 25px;"><b>Invoice Number -</b> {{$invoiceNumber}}</p>
            <p style="margin: 0; padding: 0; border: 0; line-height: 28px; padding-bottom: 25px;"><b>Amount -</b> {{$amount}}</p>
        </div>
    </div>
</body>
</html>
