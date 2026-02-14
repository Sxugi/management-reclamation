/**
 * Gudang Form Component
 * Alpine.js Component for Warehouse Management Form
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('gudangForm', (config) => ({
        // Form State
        transactionType: config.initialType,
        category: config.initialCategory,
        allItems: config.items,
        availableItems: [],
        selectedItemName: '',
        manualItemName: config.initialName,
        sku: config.initialSku,
        unit: config.initialUnit,
        isNewItemMode: false,
        isEdit: config.isEdit,
        
        // Stock Checking State
        jumlahBarang: config.initialJumlah || 0,
        statusBarang: config.initialStatus || '',
        namaBarang: config.initialName || '',
        stokTersedia: null,
        isCheckingStock: false,
        stockCheckError: null,
        
        // API Config
        lahanId: config.lahanId,
        gudangId: config.gudangId || null,
        checkStockUrl: config.checkStockUrl,

        /**
         * Initialize Component
         */
        init() {
            if (this.category) {
                this.updateAvailableItems();
                
                // Handle initial item selection
                if (config.initialName) {
                    const match = this.availableItems.find(i => i.nama_barang === config.initialName);
                    if (match) {
                        this.selectedItemName = config.initialName;
                        this.isNewItemMode = false;
                        this.fillItemDetails();
                    } else {
                        this.isNewItemMode = true;
                        this.manualItemName = config.initialName;
                    }
                } else if (this.availableItems.length === 0) {
                    this.isNewItemMode = true;
                    if (!this.isEdit) this.generateSku();
                }
            } else {
                if (config.initialName) {
                    this.manualItemName = config.initialName;
                }
            }

            // Initial stock check for KELUAR transaction
            if (this.transactionType === 'KELUAR' && this.namaBarang) {
                this.checkStock();
            }

            // Watch for changes
            this.$watch('transactionType', () => {
                if (this.transactionType === 'KELUAR' && this.namaBarang) {
                    this.checkStock();
                } else {
                    this.resetStockCheck();
                }
            });

            this.$watch('jumlahBarang', () => {
                if (this.transactionType === 'KELUAR' && this.namaBarang && this.jumlahBarang > 0) {
                    // Debounce untuk mengurangi API calls
                    clearTimeout(this.stockCheckTimeout);
                    this.stockCheckTimeout = setTimeout(() => {
                        this.checkStock();
                    }, 300);
                }
            });
        },

        /**
         * Category Change Handler
         */
        onCategoryChange() {
            this.updateAvailableItems();
            this.resetItemSelection();
            
            if (this.availableItems.length === 0) {
                this.isNewItemMode = true;
                this.generateSku();
            } else {
                this.isNewItemMode = false;
            }

            // Reset stock check when category changes
            this.resetStockCheck();
        },

        /**
         * Update available items based on category
         */
        updateAvailableItems() {
            this.availableItems = this.allItems[this.category] || [];
        },

        /**
         * Reset item selection
         */
        resetItemSelection() {
            if (!this.manualItemName && !this.selectedItemName) {
                this.selectedItemName = '';
                this.manualItemName = '';
                this.sku = '';
                this.unit = '';
                this.namaBarang = '';
            }
        },

        /**
         * Toggle between new item mode and select mode
         */
        toggleNewItemMode() {
            this.isNewItemMode = !this.isNewItemMode;
            this.selectedItemName = '';
            this.manualItemName = '';
            this.sku = '';
            this.unit = '';
            this.namaBarang = '';
            this.resetStockCheck();
            
            if (this.isNewItemMode) this.generateSku();
        },

        /**
         * Fill item details from database
         */
        fillItemDetails() {
            const item = this.availableItems.find(i => i.nama_barang === this.selectedItemName);
            if (item) {
                this.sku = item.sku || '';
                this.unit = item.satuan || '';
                this.namaBarang = item.nama_barang;
                
                // Check stock when item selected
                if (this.transactionType === 'KELUAR') {
                    this.checkStock();
                }
            }
        },

        /**
         * Handle manual item name input
         */
        onManualNameChange() {
            this.namaBarang = this.manualItemName;
            
            if (this.transactionType === 'KELUAR' && this.namaBarang) {
                // Debounce untuk mengurangi API calls
                clearTimeout(this.nameCheckTimeout);
                this.nameCheckTimeout = setTimeout(() => {
                    this.checkStock();
                }, 500);
            }
        },

        /**
         * Generate SKU based on category
         */
        generateSku() {
            if (!this.category) return;
            
            const prefixMap = {
                'Pupuk': 'PUK',
                'Pestisida': 'PES',
                'Benih': 'BEN',
                'Alat Pertanian': 'ALT',
                'Bahan Bakar': 'BBR',
                'Lainnya': 'OTH'
            };
            
            const prefix = prefixMap[this.category] || 'GEN';
            const timestamp = new Date().getTime().toString().slice(-6);
            const random = Math.floor(100 + Math.random() * 900);
            
            this.sku = `${prefix}-${timestamp}-${random}`;
        },

        /**
         * Check available stock via API
         */
        async checkStock() {
            if (!this.namaBarang || this.transactionType !== 'KELUAR') {
                this.resetStockCheck();
                return;
            }

            this.isCheckingStock = true;
            this.stockCheckError = null;

            try {
                const url = new URL(this.checkStockUrl, window.location.origin);
                url.searchParams.append('nama_barang', this.namaBarang);
                
                if (this.gudangId) {
                    url.searchParams.append('exclude_id', this.gudangId);
                }

                const response = await fetch(url.toString(), {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch stock data');
                }

                const data = await response.json();
                this.stokTersedia = data.stok || 0;

            } catch (error) {
                console.error('Stock check error:', error);
                this.stockCheckError = 'Gagal mengecek stok. Silakan periksa koneksi Anda.';
                this.stokTersedia = null;
            } finally {
                this.isCheckingStock = false;
            }
        },

        /**
         * Reset stock check state
         */
        resetStockCheck() {
            this.stokTersedia = null;
            this.isCheckingStock = false;
            this.stockCheckError = null;
        },

        /**
         * Computed: Remaining stock after transaction
         */
        get sisaStok() {
            if (this.stokTersedia === null || this.jumlahBarang <= 0) {
                return null;
            }
            return this.stokTersedia - this.jumlahBarang;
        },

        /**
         * Computed: Will stock be empty after transaction?
         */
        get willBeEmpty() {
            return this.sisaStok === 0;
        },

        /**
         * Computed: Will stock remain after transaction?
         */
        get willHaveStock() {
            return this.sisaStok !== null && this.sisaStok > 0;
        },

        /**
         * Computed: Is stock insufficient?
         */
        get isStockInsufficient() {
            return this.sisaStok !== null && this.sisaStok < 0;
        },

        /**
         * Computed: Should show stock info?
         */
        get shouldShowStockInfo() {
            return this.transactionType === 'KELUAR' && 
                   this.stokTersedia !== null && 
                   !this.isCheckingStock;
        },

        /**
         * Computed: Stock status color class
         */
        get stockStatusClass() {
            if (this.isStockInsufficient) {
                return 'bg-red-50 border-red-200 text-red-700';
            }
            if (this.willBeEmpty) {
                return 'bg-orange-50 border-orange-200 text-orange-700';
            }
            if (this.willHaveStock) {
                return 'bg-green-50 border-green-200 text-green-700';
            }
            if (this.stokTersedia > 0) {
                return 'bg-blue-50 border-blue-200 text-blue-700';
            }
            return 'bg-gray-50 border-gray-200 text-gray-700';
        }
    }));
});