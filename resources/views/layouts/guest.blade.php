<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Griya Kreatif Deyalina') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/deyalina.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #faf8f5;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .btn-back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            background: white;
            color: #4a154b;
            border: 1px solid #ddd;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            z-index: 10;
        }
        .btn-back-home:hover {
            background: #4a154b;
            color: white;
            border-color: #4a154b;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            margin: 20px;
            padding: 2.5rem 2rem;
            border: none;
        }

        /* Styling untuk input bawaan Breeze (Tailwind) agar selaras */
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            background-color: #f8f9fa;
            transition: 0.3s;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border-color: #4a154b;
            box-shadow: 0 0 0 3px rgba(74, 21, 75, 0.1);
            outline: none;
            background-color: white;
        }
        
        button[type="submit"] {
            background: linear-gradient(to right, #4a154b, #8a264a);
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
            color: white;
            transition: 0.3s;
        }
        button[type="submit"]:hover {
            opacity: 0.9;
        }

        /* Teks link */
        a {
            color: #d32f2f;
            text-decoration: none;
            font-weight: 600;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    
    <a href="{{ route('home') }}" class="btn-back-home">
        <i class="bi bi-arrow-left me-1"></i> Beranda
    </a>

    <div class="auth-card">
        {{ $slot }}
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>