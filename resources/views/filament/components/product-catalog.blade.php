<div x-data="productCatalog">
    <!-- Search Bar -->
    <div class="mb-2">
        <input type="text" x-model="search" placeholder="🔍 Cari produk..."
            class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-md
                   focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                   dark:bg-gray-800 dark:border-gray-600 dark:text-white">
    </div>

    <!-- Product Grid - Compact -->
    <div class="flex flex-wrap gap-4 max-h-[400px] overflow-y-auto">
        @foreach ($products as $product)
            <button type="button"
                @click="addProduct({{ $product->id }}, '{{ addslashes($product->nama) }}', {{ $product->harga }})"
                class="group w-[85px] border border-gray-200 rounded-md p-1
                       cursor-pointer hover:border-primary-500 hover:shadow-sm
                       transition-all bg-white dark:bg-gray-800
                       dark:border-gray-700 hover:scale-105 active:scale-95"
                x-show="search === '' || '{{ strtolower($product->nama) }}'.includes(search.toLowerCase())">

                <div class="h-[60px] w-full bg-gray-100 dark:bg-gray-700 rounded-sm mb-1 overflow-hidden">
                    <div class="flex items-center justify-center w-full h-full text-lg">
                        📦
                    </div>
                </div>

                <div class="text-[9px] text-center leading-tight line-clamp-2">
                    {{ $product->nama }}
                </div>

                <div class="text-[9px] font-semibold text-primary-600 text-center">
                    Rp {{ number_format($product->harga / 1000, 0) }}k
                </div>
            </button>
        @endforeach
    </div>

    <!-- Empty State -->
    <div x-show="search !== '' && ![...document.querySelectorAll('[x-show]')].some(el => el.offsetParent !== null)"
        class="text-center py-6">
        <div class="text-gray-400 text-3xl mb-1">🔍</div>
        <p class="text-gray-500 text-xs">Produk tidak ditemukan</p>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('productCatalog', () => ({
            search: '',

            addProduct(productId, productName, price) {
                let items = this.$wire.get('data.invoice_items') || [];

                let existingIndex = items.findIndex(item => item.product_id === productId);

                if (existingIndex !== -1) {
                    items[existingIndex].qty = (parseInt(items[existingIndex].qty) || 0) + 1;
                    items[existingIndex].total = items[existingIndex].qty * items[existingIndex]
                        .price;
                } else {
                    items.push({
                        product_id: productId,
                        product_name: productName,
                        qty: 1,
                        price: price,
                        total: price
                    });
                }

                this.$wire.set('data.invoice_items', items);

                const button = event.currentTarget;
                button.classList.add('ring-2', 'ring-green-500');
                setTimeout(() => {
                    button.classList.remove('ring-2', 'ring-green-500');
                }, 200);
            }
        }));
    });
</script>
