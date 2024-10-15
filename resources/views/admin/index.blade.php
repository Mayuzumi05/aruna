<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
  @import url('https://fonts.googleapis.com/css2?family=Heebo:wght@100..900&family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap');
  </style>
  @vite('resources/css/app.css')
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-[#FCFEFD]">
  <div class="p-[24px]">
    <div>
        <button class="bg-[#6A9C89] hover:bg-[#16423C] px-[24px] py-[16px] rounded-[8px] flex" data-bs-toggle="modal" data-bs-target="#tambahMenuModal">
            <img src="img/plus.svg" alt="">
            <p class="poppins-regular ml-[8px] mb-0 text-white">Add Menu</p>
        </button>
    </div>
  </div>
  <div class="modal fade" id="tambahMenuModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Menu</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
                <div class="form-group">
                    <label for="name" class="col-form-label">Name</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
                <div class="form-group">
                    <label for="image" class="col-form-label">Image</label>
                    <input type="file" class="form-control" name="image" id="image">
                </div>
                <div class="form-group">
                    <label for="price" class="col-form-label">Price</label>
                    <input type="text" class="form-control" name="price" id="price">
                </div>
                <div class="form-group">
                    <label for="category" class="col-form-label">Category</label>
                    <select class="form-select" id="inputGroupSelect01" name="category_id">
                        @foreach($categories as $items)
                            <option value="{{ $items->id }}">{{ $items->name }}</option>
                        @endforeach
                    </select>
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="bg-[#F6FAF7] px-[24px] py-[16px] rounded-[8px]" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="bg-[#6A9C89] hover:bg-[#16423C] px-[24px] py-[16px] rounded-[8px] text-white">Save changes</button>
        </div>
        </form>
        </div>
    </div>
    </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
      $(document).ready(function(){
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
                                  <div class="menu-item bg-white p-[16px] rounded-[8px]">
                                      <img src="img/mie.jpg" alt="${menu.name}" class="rounded-[8px] max-w-48 max-h-48">
                                      <p class="mt-[16px] mb-[4px] text-base poppins-regular">${menu.name}</p>
                                      <p class="text-xl poppins-medium">Rp. ${menu.price}</p>
                                  </div>
                              `;
                              $('#menu-container').append(menuHtml);
                          });
                      } else {
                          $('#menu-container').append('<p>No menu available for this category.</p>');
                      }
                  },
                  error: function() {
                      alert('Error loading menus.');
                  }
              });
          });
      });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>