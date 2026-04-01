<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataGudang extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_gudang';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'data_gudang_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lahan_id',
        'tanggal_masuk',
        'jenis_transaksi',
        'sku',
        'jenis_barang',
        'nama_barang',
        'jumlah_barang',
        'satuan',
        'lokasi_penyimpanan',
        'status_barang',
        'catatan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    public function getRouteKeyName()
    {
        return 'data_gudang_id';
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        // Auto-update status when creating
        static::creating(function ($dataGudang) {
            $dataGudang->autoUpdateStatusIfEmpty();
        });
        
        // Auto-update status when updating
        static::updating(function ($dataGudang) {
            $dataGudang->autoUpdateStatusIfEmpty();
        });
    }

    /**
     * Auto-update status if not provided or empty
     */
    public function autoUpdateStatusIfEmpty(): void
    {
        // If status is not set or empty, calculate it automatically
        if (empty($this->status_barang)) {
            $this->autoCorrectStatus();
        }
    }

    /**
     * Auto-correct status based on stock calculation
     */
    public function autoCorrectStatus(): void
    {
        if ($this->jenis_transaksi === 'KELUAR') {
            $stokSetelah = $this->calculateStockAfter();
            
            // Auto-correct to "Kosong" if stock is zero
            if ($stokSetelah === 0) {
                $this->status_barang = 'Kosong';
            }
            
            // If stock remains but status is "Kosong"
            if ($stokSetelah > 0 && $this->status_barang === 'Kosong') {
                // Auto-correct to "Tersedia"
                $this->status_barang = 'Tersedia';
            }
        }
        
        // If transaction is MASUK, ensure status is not "Kosong"
        if ($this->jenis_transaksi === 'MASUK') {
            $stokSetelah = $this->calculateStockAfter();
            
            if ($stokSetelah > 0 && $this->status_barang === 'Kosong') {
                $this->status_barang = 'Tersedia';
            }
        }
    }

    /**
     * Calculate stock after this transaction
     */
    public function calculateStockAfter(): int
    {
        $query = self::where('lahan_id', $this->lahan_id)
            ->where('nama_barang', $this->nama_barang);
        
        // Exclude current transaction if updating
        if ($this->exists) {
            $query->where('data_gudang_id', '!=', $this->data_gudang_id);
        }
        
        $stokExisting = $query->selectRaw("
            SUM(CASE 
                WHEN jenis_transaksi = 'MASUK' THEN jumlah_barang 
                ELSE -jumlah_barang 
            END) as sisa
        ")->value('sisa') ?? 0;
        
        return $stokExisting - $this->jumlah_barang;
    }

    /**
     * Get the lahan that owns the DataGudang.
     */
    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class, 'lahan_id', 'lahan_id');
    }
}
