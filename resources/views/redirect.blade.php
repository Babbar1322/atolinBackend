<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Atolin</title>
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100dvh;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Redirecting</h1>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = 'atolin://home';
        }, 1000);
    </script>
</body>
</html>
