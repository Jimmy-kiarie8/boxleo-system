<?php

namespace App\Models\Warehouse;

use App\Models\Product;
use App\Scopes\BinScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bin extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'area_id', 'bay_id', 'level_id', 'row_id', 'warehouse_id'];

    // public $with = ['warehouse', 'area', 'row', 'bay', 'level'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
    public function row()
    {
        return $this->belongsTo(Row::class, 'row_id');
    }
    public function bay()
    {
        return $this->belongsTo(Bay::class, 'bay_id');
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    /**
     * The products that belong to the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_bins', 'product_id', 'bin_id')->withPivot(['onhand', 'available_for_sale', 'commited']);
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('d M Y');
    }

    public function occupied()
    {
    }
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BinScope);
    }
}
