<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>QR Code - Mesa {{ $mesa->numero }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 40px;
            background: #fff;
        }
        .ticket {
            border: 2px dashed #000;
            padding: 30px;
            display: inline-block;
            border-radius: 10px;
        }
        h1 {
            font-size: 40px;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }
        p {
            font-size: 18px;
            color: #555;
            margin: 0 0 20px 0;
        }
        .qr-wrapper {
            margin: 20px auto;
        }
        .url {
            font-size: 14px;
            color: #777;
            margin-top: 10px;
        }
        @media print {
            .no-print { display: none; }
            .ticket { border: none; }
        }
    </style>
</head>
<body>

    <button class="no-print" onclick="window.print()" style="padding:10px 20px; font-size:16px; margin-bottom: 20px; cursor: pointer;">
        🖨️ Imprimir
    </button>

    <div class="ticket">
        <h1>MESA {{ $mesa->numero }}</h1>
        <p>Aponte a câmera do seu celular <br>para acessar o cardápio</p>
        
        <div class="qr-wrapper">
            {!! $svg !!}
        </div>

        <div class="url">
            {{ $url }}
        </div>
    </div>

</body>
</html>
