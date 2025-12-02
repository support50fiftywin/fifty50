<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>50Fifty Sweepstakes</title>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #000;
            color: #fff;
        }
        .hero {
            background: url('/images/hero-car.jpg') center/cover no-repeat;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 20px;
        }
        .hero h1 {
            font-size: 50px;
            font-weight: 900;
            margin-bottom: 10px;
        }
        .hero p {
            font-size: 20px;
            max-width: 600px;
            margin-bottom: 30px;
        }
        .btn {
            background: #ff3b3b;
            padding: 14px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 18px;
            color: #fff;
            font-weight: bold;
            transition: 0.3s;
            margin: 5px;
        }
        .btn:hover {
            background: #d92828;
        }
        .links {
            position: absolute;
            top: 25px;
            right: 25px;
        }
        .links a {
            color: #fff;
            margin-left: 12px;
            font-size: 15px;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="links">
        @auth
            <a href="/dashboard"><i class="fa fa-gauge"></i> Dashboard</a>
        @else
            <a href="/login"><i class="fa fa-right-to-bracket"></i> Login</a>
            <a href="/register"><i class="fa fa-user-plus"></i> Register</a>
        @endauth
    </div>

    <section class="hero">
        <h1>WIN MONTHLY SUPERCARS & PRIZES!</h1>
        <p>Enter the 50Fifty Sweepstakes for a chance to win cars, bikes, gadgets, and more. 
        Every purchase gives you entries — more entries, more chances.</p>

        <a href="/register" class="btn">Join the Sweepstakes</a>
        <a href="/merchant-register" class="btn">Become a Merchant Partner</a>
    </section>

</body>
</html>
