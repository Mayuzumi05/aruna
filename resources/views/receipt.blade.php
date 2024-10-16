<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Receipt</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Heebo:wght@100..900&family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap');
    </style>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="receipt bg-white p-4 rounded-lg shadow-lg">
        <div class="flex flex-col items-center justify-center h-screen">
            <p class="text-base">ARUNA PANTRY</p>
            <p class="text-base">Jl. Sportivo 4 No.7, Suci, Tebalo, Kec. Manyar, Kabupaten Gresik, Jawa Timur 61151</p>
            <p class="text-base">085706189706</p>
        </div>
        <hr class="border-t-2 border-dashed border-black" />
        <div class="mb-4 text-sm">
            <p class="text-base">Order ID &emsp;: {{ $order->id }}</p>
            <p class="text-base">Tanggal &emsp;: {{ $order->created_at }}</p>
            <p class="text-base">Status &emsp;: {{ $order->status }}</p>
        </div>
        <hr class="border-t-2 border-dashed border-black" />
        @foreach($cartItems as $item)
        <p>{{ $item['name'] }}</p>
        <div class="flex justify-between">
            <p class="text-base">{{ $item['quantity'] }}x &ensp;Rp{{ number_format($item['price'], 2) }}</p>
            <p class="text-base">Rp{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
        </div>
        @endforeach
        <hr class="border border-dashed border-t-2 border-black" />
        <br>
        <p class="text-xl font-semibold">Total: Rp{{ number_format($order->total, 2) }}</p>
        <br>
        <hr class="border-t-2 border-dashed border-black" />
        <div class="flex flex-col items-center justify-center h-screen">
            <p class="text-base text-center">Terima kasih telah berbelanja!</p>
        </div>
    </div>
</body>
</html>
