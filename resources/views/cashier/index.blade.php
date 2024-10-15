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
    <div class="categories">
      @foreach($categories as $category)
        <button class="btn btn-primary filter-category" data-category-id="{{ $category->id }}">
          <div class="bg-white p-[16px] rounded-[8px] mr-[16px]">
            <img src="img/food-icon.svg" alt="">
            <p class="poppins-regular mt-[16px]">{{ $category->name }}</p>
          </div>
        </button>
      @endforeach
      <button class="btn btn-secondary filter-category" data-category-id="all">
        <div class="bg-white p-[16px] rounded-[8px] mr-[16px]">
          <img src="img/food-icon.svg" alt="">
          <p class="poppins-regular mt-[16px] sm:mt-[16px]">All Menu</p>
        </div>
      </button>
    </div>
    <label class="relative block my-[16px]">
      <span class="sr-only">Search</span>
      <span class="absolute inset-y-0 left-0 flex items-center pl-2">
        <svg class="h-5 w-5 fill-slate-300" viewBox="0 0 20 20"><!-- ... --></svg>
      </span>
      <input class="placeholder:italic placeholder:text-slate-400 block bg-white w-full rounded-md py-2 pl-9 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm" placeholder="Search for anything..." type="text" name="search"/>
    </label>
    <div id="menu-container" class="grid sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-[24px] my-[16px] w-fit">
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
                                  <div class="menu-item bg-white p-[16px] rounded-[8px] hover:shadow-lg cursor-pointer border border-[#E9EFEC]">
                                      <img src="${menu.image ? `/storage/img/${menu.image}` : '/img/default-icon.svg'}" alt="${menu.name}" class="rounded-[8px] w-48 h-48">                                      
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
</body>
</html>