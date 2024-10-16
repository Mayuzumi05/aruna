<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">  
  <style>
  @import url('https://fonts.googleapis.com/css2?family=Heebo:wght@100..900&family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap');
  </style>
  @vite('resources/css/app.css')
</head>
<body class="bg-[#FCFEFD]">
  <div class="p-[24px]">
    <div class="container">
      <div class="flex">
        <div class="w-2/3">
          <div class="categories">
            @foreach($categories as $category)
              <button class="btn btn-primary filter-category" data-category-id="{{ $category->id }}">
                <div class="bg-white p-[16px] rounded-[8px] mr-[16px] border border-[#E9EFEC] hover:shadow-md">
                  <img src="img/food-icon.svg" alt="">
                  <p class="poppins-regular mt-[16px]">{{ $category->name }}</p>
                </div>
              </button>
            @endforeach
            <button class="btn btn-secondary filter-category" data-category-id="all">
              <div class="bg-white p-[16px] rounded-[8px] mr-[16px] border border-[#E9EFEC] hover:shadow-md">
                <img src="img/food-icon.svg" alt="">
                <p class="poppins-regular mt-[16px] sm:mt-[16px]">All Menu</p>
              </div>
            </button>
          </div>
          <label class="relative block my-[16px]">
            <span class="sr-only">Search</span>
            <span class="absolute inset-y-0 left-0 flex items-center pl-2">
              <svg class="h-5 w-5 fill-slate-300" viewBox="0 0 20 20">
                <!-- SVG icon -->
              </svg>
            </span>
            <input class="placeholder:italic placeholder:text-slate-400 block bg-white w-full rounded-md py-2 pl-9 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm border border-[#E9EFEC]" placeholder="Search for anything..." type="text" name="search"/>
          </label>
          <div id="menu-container" class="grid sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-[24px] my-[16px] w-fit"></div>
        </div>
        <div class="w-1/3 p-[16px] bg-white rounded-[16px] border border-[#E9EFEC] ml-[16px]">
          <h4 class="text-lg poppins-medium mb-[16px]">Keranjang</h4>
          <div id="cart-container" class="space-y-4">
              <!-- Item dalam keranjang akan muncul di sini -->
          </div>
          <div class="mt-4">
              <p class="text-2xl poppins-semibold text-[#6A9C89]">Total: <span id="cart-total">0</span></p>
              <button id="checkout-btn" class="mt-[16px] px-[24px] py-[12px] bg-[#6A9C89] text-white rounded hover:bg-[#16423C]">Checkout</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function(){
        // Filter kategori
        $('.filter-category').on('click', function(){
            var categoryId = $(this).data('category-id');
            var url = categoryId === 'all' ? '{{ route("cashier.index") }}' : '/cashier/category/' + categoryId;

            $.ajax({
                url: url,
                type: 'GET',
                success: function(menus) {
                    $('#menu-container').empty(); // Kosongkan area menu sebelum diisi
                    if (menus.length > 0) {
                        menus.forEach(function(menu) {
                            var menuHtml = `
                                <div class="menu-item bg-white p-[16px] rounded-[8px] hover:shadow-lg cursor-pointer border border-[#E9EFEC]">
                                    <img src="${menu.image ? `/storage/img/${menu.image}` : '/img/default-icon.svg'}" alt="${menu.name}" class="rounded-[8px] w-48 h-48">                                      
                                    <p class="mt-[16px] mb-[4px] text-base poppins-regular">${menu.name}</p>
                                    <p class="text-xl poppins-medium">Rp. ${menu.price}</p>
                                    <button class="mt-[16px] px-[24px] py-[12px] bg-[#6A9C89] text-white rounded hover:bg-[#16423C] add-to-cart" data-id="${menu.id}" data-price="${menu.price}" data-name="${menu.name}">
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            `;
                            $('#menu-container').append(menuHtml);
                        });

                        // Attach add-to-cart event listeners
                        attachAddToCartListeners();
                    } else {
                        $('#menu-container').append('<p>No menu available for this category.</p>');
                    }
                },
                error: function() {
                    alert('Error loading menus.');
                }
            });
        });

        // Tambah ke keranjang
        function attachAddToCartListeners() {
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function() {
                    let menuItem = {
                        id: this.getAttribute('data-id'),
                        name: this.getAttribute('data-name'),
                        price: parseFloat(this.getAttribute('data-price')),
                        quantity: 1
                    };
                    addToCart(menuItem);
                });
            });
        }

        let cart = [];

        function addToCart(menuItem) {
            let existingItem = cart.find(item => item.id === menuItem.id);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push(menuItem);
            }
            updateCart();
        }

        function updateCart() {
            let cartContainer = document.getElementById('cart-container');
            cartContainer.innerHTML = '';
            let total = 0;
            cart.forEach((item, index) => {
                total += item.price * item.quantity;
                cartContainer.innerHTML += `
                    <div class="flex justify-between items-center bg-white p-4 shadow rounded">
                        <div>
                            <span class="text-lg font-semibold">${item.name}</span>
                            <p>Rp${(item.price * item.quantity).toFixed(2)}</p>
                        </div>
                        <div class="flex items-center">
                            <input type="number" min="1" value="${item.quantity}" data-index="${index}" class="item-quantity w-16 text-center border border-gray-300 rounded">
                            <button class="ml-4 text-red-500 hover:text-red-600 remove-item-btn" data-index="${index}">
                                <img src="img/delete-icon.svg" alt="">
                            </button>
                        </div>
                    </div>
                `;
            });
            document.getElementById('cart-total').innerText = total.toFixed(2);

            // Event listener untuk menghapus item
            document.querySelectorAll('.remove-item-btn').forEach(button => {
                button.addEventListener('click', function() {
                    removeFromCart(button.getAttribute('data-index'));
                });
            });

            // Event listener untuk mengubah jumlah item
            document.querySelectorAll('.item-quantity').forEach(input => {
                input.addEventListener('change', function() {
                    updateQuantity(input.getAttribute('data-index'), input.value);
                });
            });
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCart();
        }

        function updateQuantity(index, quantity) {
            cart[index].quantity = parseInt(quantity);
            updateCart();
        }

        // Checkout
        document.getElementById('checkout-btn').addEventListener('click', function() {
            fetch('/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ cart: cart })
            })
            .then(response => {
                // Periksa apakah response status berhasil
                if (!response.ok) {
                    throw new Error('Gagal mengambil data PDF');
                }
                return response.blob(); // Terima respons dalam bentuk Blob
            })
            .then(blob => {
                // Membuat URL Blob untuk file PDF yang akan diunduh
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'receipt.pdf'); // Nama file PDF
                document.body.appendChild(link);
                link.click(); // Unduh file
                link.parentNode.removeChild(link); // Hapus link setelah di-download
                
                // Cetak langsung dari URL Blob tanpa membuka jendela baru
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none'; // Sembunyikan iframe
                iframe.src = url; // Set iframe ke URL Blob PDF
                document.body.appendChild(iframe); // Tambahkan ke dokumen
                
                iframe.onload = function() {
                    iframe.contentWindow.print(); // Cetak PDF setelah iframe selesai memuat
                };
            })
            .catch(error => {
                alert('Terjadi kesalahan saat checkout: ' + error.message);
            });
        });
    });
  </script>
</body>
</html>