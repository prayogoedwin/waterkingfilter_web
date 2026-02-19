<div x-data="invoiceCart" class="border border-gray-200 rounded-lg bg-gray-50 mb-4">
    <div class="px-4 py-2 bg-gray-100 border-b border-gray-200 font-semibold text-sm text-gray-700">
        🛒 Keranjang Belanja
    </div>

    <div class="max-h-[300px] overflow-y-auto">
        <template x-for="(item, index) in items" :key="index">
            <div class="px-4 py-3 border-b border-gray-200 hover:bg-gray-100 transition">
                <!-- Product Name -->
                <div class="flex items-center justify-between mb-2">
                    <span class="font-medium text-sm text-gray-900" x-text="item.product_name"></span>
                    <button type="button" @click="removeItem(index)"
                        class="text-red-500 hover:text-red-700 text-xs font-bold">
                        ✕
                    </button>
                </div>

                <!-- Qty & Price -->
                <div class="flex items-center justify-between text-xs text-gray-600">
                    <div class="flex items-center gap-2">
                        <button type="button" @click="decreaseQty(index)"
                            class="w-6 h-6 bg-gray-200 hover:bg-gray-300 rounded flex items-center justify-center font-bold">−</button>

                        <input type="number" :value="item.qty" @input="updateQty(index, $event.target.value)"
                            @click.stop class="w-12 text-center border border-gray-300 rounded py-1" min="1">

                        <button type="button" @click="increaseQty(index)"
                            class="w-6 h-6 bg-gray-200 hover:bg-gray-300 rounded flex items-center justify-center font-bold">+</button>
                    </div>

                    <div class="flex flex-col items-end">
                        <span class="text-gray-500">@ Rp <span x-text="formatPrice(item.price)"></span></span>
                        <span class="font-bold text-sm text-gray-900">Rp <span
                                x-text="formatPrice(item.total)"></span></span>
                    </div>
                </div>
            </div>
        </template>

        <!-- Empty State -->
        <div x-show="items.length === 0" class="px-4 py-8 text-center text-gray-400 text-sm">
            Belum ada produk dipilih
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('invoiceCart', () => ({
            items: [],

            init() {
                // Watch perubahan dari Livewire
                this.$watch('$wire.data.invoice_items', value => {
                    this.items = value || [];
                });

                // Set initial value
                this.items = this.$wire.get('data.invoice_items') || [];
            },

            updateQty(index, qty) {
                qty = parseInt(qty) || 1;
                this.items[index].qty = qty;
                this.items[index].total = qty * this.items[index].price;
                this.$wire.set('data.invoice_items', this.items);
            },

            increaseQty(index) {
                this.items[index].qty = (parseInt(this.items[index].qty) || 0) + 1;
                this.items[index].total = this.items[index].qty * this.items[index].price;
                this.$wire.set('data.invoice_items', this.items);
            },

            decreaseQty(index) {
                if (this.items[index].qty > 1) {
                    this.items[index].qty = parseInt(this.items[index].qty) - 1;
                    this.items[index].total = this.items[index].qty * this.items[index].price;
                    this.$wire.set('data.invoice_items', this.items);
                }
            },

            removeItem(index) {
                this.items.splice(index, 1);
                this.$wire.set('data.invoice_items', this.items);
            },

            formatPrice(price) {
                return new Intl.NumberFormat('id-ID').format(price);
            }
        }));
    });
</script>
