<?php

namespace App\Models;

use App\Models\discounts\DiscountCriteriaModel;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\Warehouse;
use App\Scopes\ProductScope;
use App\Scopes\SellerproductScope;
use App\Seller;
use App\Traits\InventoryWarehouseManagement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use SoftDeletes;
    use InventoryWarehouseManagement;

    protected $fillable = ['product_name', 'user_id', 'vendor_id', 'sku_no', 'stock_management', 'active'];
    public $with = ['product_variants', 'skus', 'categories', 'brands', 'subcategories', 'images', 'bins'];




    protected $casts = [
        'stock_management' => 'string',
        'virtual' => 'boolean',
        'active' => 'boolean'
    ];

    public function sales()
    {
        return $this->belongsToMany(Sale::class);
        // return $this->belongsToMany('App\Models\Sale')->using('App\Models\ProductSale');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class);
    }

    /**
     * Get all of the shipments for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(ShipmentProduct::class, 'product_id');
    }

    /**
     * Get all of the daily_stats for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function daily_stats(): HasMany
    // {
    //     return $this->hasMany(DailyStats::class, 'product_id');
    // }


    public function dailyStats(): HasMany
    {
        return $this->hasMany(DailyStats::class, 'product_id');
    }

    // public function getPriceAttribute($price)
    // {
    //     return format_currency($price);
    // }

    // Product relationships
    public function product_variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function product_options()
    {
        return $this->hasMany(ProductOption::class);
    }
    public function images()
    {
        return $this->hasOne(Image::class);
    }
    // public function variant_values()
    // {
    //     return $this->hasMany(VariantValue::class);
    // }
    public function skus()
    {
        return $this->hasMany(Sku::class);
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'vendor_id');
    }

    public function discounts()
    {
        return $this->morphMany(
            DiscountCriteriaModel::class,
            'eligible'
        )->with('criteria.set.discount');
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'product_bins')->withPivot('id', 'product_id');
    }

    /**
     * Get all of the transfers for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class);
    }

    /**
     * The roles that belong to the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    /* public function inventories(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class,'product_inventories');
    }
    public function inventories()
    {
        return $this->belongsToMany(Warehouse::class, 'product_inventories')->withPivot('id',  'warehouse_id', 'product_id');
    } */
    public function inventory()
    {
        return $this->hasMany(ProductInventory::class, 'product_id');
    }

    /**
     * The bins that belong to the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bins(): BelongsToMany
    {
        return $this->belongsToMany(Bin::class, 'product_bins', 'product_id', 'bin_id')->withPivot(['onhand', 'available_for_sale', 'commited', 'delivered']);
    }

    public function productHistory()
    {
        return $this->hasMany(ProductHistory::class, 'product_id');
    }
    public function receives()
    {
        return $this->hasMany(ProductReceive::class);
    }
    public function search($search)
    // public function search($search, $vendor_id)
    {
        return Product::where('product_name', 'LIKE', "%{$search}%")->first();
        // return Product::where('product_name', 'LIKE', "%{$search}%")->where('vendor_id', $vendor_id)->first();
    }

    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }

    // public static function booted()
    // {
    //     static::addGlobalScope('product_name', function (Builder $builder) {
    //         $builder->orderBy('created_at', 'DESC');
    //     });
    // }
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SellerproductScope);
        static::addGlobalScope(new ProductScope);

        static::deleting(function ($telco) {
            $relationMethods = ['sales'];

            foreach ($relationMethods as $relationMethod) {
                if ($telco->$relationMethod()->count() > 0) {
                    abort(422, 'The product cannot be deleted because it contains ' . $relationMethod);
                    // return false;
                }
            }
        });

        parent::boot();

        static::updated(function (Product $product) {
            $cacheKey = 'product_' . $product->id;
            Cache::forget($cacheKey);
        });

        // static::created(function () {
        //     $cacheKey = 'products_' . Auth::user()->ou_id;
        //     Cache::forget($cacheKey);
        // });
    }

    public function product_compare($sku, $s1, $vendor_id)
    {
        if (Auth::guard('seller')->check()) {
            $vendor_id = Auth::guard('seller')->id();
        }

        $products = null;

        if ($sku) {
            $products = Product::setEagerLoads([])->where('active', true)->where('vendor_id', $vendor_id)->where('sku_no', $sku)->first();
        }

        if (!$products) {
            $products = Product::setEagerLoads([])->where('active', true)->where('vendor_id', $vendor_id)->where('product_name', $s1)->first(['id', 'product_name', 'sku_no']);
        }


        if ($products) {
            return $products->id;
        } else {
            return null;
            // return $this->check($s1, $vendor_id);
        }
    }


    public function checkProductMatch($product_name, $vendor_id)
    {
        // Product being uploaded
        $product_name = 'NEW Lift Up Invisible Bra Tape';
        $existing_product_name = 'Pants';
        $products = Product::setEagerLoads([])->where('vendor_id', $vendor_id)->get(['id', 'product_name', 'sku_no']);

        // Existing product in the database
        // $existing_product_name = Product::where('product_name', $product_name)->first();

        // Calculate the Levenshtein distance between the two strings
        $distance = levenshtein($product_name, $existing_product_name);

        // Set the threshold for what constitutes a close enough match
        $threshold = 5;

        // If the distance is less than or equal to the threshold, consider the products a match
        if ($distance <= $threshold) {
            return "Product is a match";
        } else {
            return "Product is not a match";
        }
    }


    public function check($s1, $vendor_id)
    {

        $products = Product::setEagerLoads([])->where('vendor_id', $vendor_id)->get(['id', 'product_name', 'sku_no']);

        // dd($products);
        $best_match = 50;
        $best_item = null;

        // $s1 = 'C14-Multifunctional Car Pocket-Black-2499KSH ';
        $s1 = strtolower($s1);

        foreach ($products as $product) {
            $s2 = $product->product_name;
            // $s2 = 'C12-Solar Car Cooler';
            // $s2 = 'C14-Multifunctional car pocket';
            $s2 = strtolower($s2);
            // dd($s1, $s2);
            //one is empty, so no result
            if (strlen($s1) == 0 || strlen($s2) == 0) {
                return 0;
            }
            // dd($s1, $s2);
            //replace none alphanumeric charactors
            //i left - in case its used to combine words
            $s1clean = preg_replace("/[^A-Za-z0-9-]/", ' ', $s1);
            $s2clean = preg_replace("/[^A-Za-z0-9-]/", ' ', $s2);

            //remove double spaces
            while (strpos($s1clean, "  ") !== false) {
                $s1clean = str_replace("  ", " ", $s1clean);
            }
            while (strpos($s2clean, "  ") !== false) {
                $s2clean = str_replace("  ", " ", $s2clean);
            }

            //create arrays
            $ar1 = explode(" ", $s1clean);
            $ar2 = explode(" ", $s2clean);
            $l1 = count($ar1);
            $l2 = count($ar2);

            //flip the arrays if needed so ar1 is always largest.
            if ($l2 > $l1) {
                $t = $ar2;
                $ar2 = $ar1;
                $ar1 = $t;
            }

            //flip array 2, to make the words the keys
            $ar2 = array_flip($ar2);


            $maxwords = max($l1, $l2);
            $matches = 0;

            //find matching words
            foreach ($ar1 as $word) {
                if (array_key_exists($word, $ar2)) {
                    $matches++;
                }
            }

            // var_dump($matches);

            $match = ($matches / $maxwords) * 100;
            // dd(($matches / $maxwords) * 100, $s1, $s2);

            if ($match >= $best_match) {
                $best_match = $match;
                $best_item = $product;
                // dd($best_item);
            }
        }
        if ($best_item != null) {
            // dd($match, $best_item);
            return $best_item->id;
        } else {
            // dd($best_item);
            // $best_item = new Product();
            return null;
            // $best_item->sku_no = null;
            // $best_item->product_name = $s1;

            // return $best_item;

        }
        // dd($matches, $best_match, $best_item);

        // return ($matches / $maxwords) * 100;
    }

    public function transform_product($products)
    {
        $products->transform(function ($product) {

            // foreach ($product->warehouse as $warehouse) {
            // $warehouse->
            // }
            // dd($product->skus);
            $product->rating = rand(0, 5);
            // dd(count($product->product_variants));
            if (count($product->product_variants) == 0) {
                if (count($product->skus) > 0) {
                    // dd(($product->skus[0]->price));
                    $product->sku_no = $product->skus[0]->sku_no;
                    $product->price = $product->skus[0]->price;
                    $product->buying_price = $product->skus[0]->buying_price;
                    $product->quantity = $product->skus[0]->quantity;
                }
            } else {
            }


            if ($product->images) {

                // foreach ($product->images as  $pro_image) {
                //     if ($pro_image->display) {
                //         $product->image = $pro_image->image;
                //     }
                // }
            }
            return $product;
        });
        return $products;
    }

    public function product_create($data, $user)
    {
        // $exist = Product::where('product_name', $data['product_name'])->first();
        // if ($exist) {
        //     return $exist;
        // }


        // DB::beginTransaction();

        try {

            if (!$data['product_name']) {
                return null;
            }

            $price = (array_key_exists('price', $data)) ? $data['price'] : 0;
            $product = new Product();
            // $product->description = $request->description;

            $product->product_name = $data['product_name'];
            $product->user_id = $user->id;
            $product->vendor_id = $data['vendor_id'];
            $product->active = true;
            $product->virtual = true;
            $product->stock_management = 0;
            // $sku_no = new AutoGenerate;
            // $sku = make_reference_id('SKU', Sku::max('id') + 1);

            $sku_no = (array_key_exists('sku_no', $data)) ? $data['sku_no'] : make_reference_id('SKU', Sku::max('id') + 1);
            $product->sku_no = $sku_no;
            // $product->sku_no =  (array_key_exists('sku_no', $data)) ? $data['sku_no'] : IdGenerator::generate(['table' => 'products', 'field' => 'sku_no', 'length' => 15, 'prefix' => 'SKU_']);;
            // $product->sku_no =  IdGenerator::generate(['table' => 'products', 'field' => 'sku_no', 'length' => 15, 'prefix' => 'SKU_']);
            $product->save();

            $sku = Sku::where('sku_no', $sku_no)->where('product_id', $product->id)->exists();

            if (!$sku) {
                $sku = Sku::create(
                    [
                        'sku_no' => $sku_no,
                        'buying_price' => $price,
                        'price' => $price,
                        'quantity' => 0,
                        'product_id' => $product->id,
                        'reorder_point' => 0
                    ]
                );
            }

            // Sku::updateOrCreate(
            //     [
            //         'sku_no' => $sku
            //     ],
            //     [
            //         'buying_price' => $price,
            //         'price' => $price,
            //         'quantity' => 0,
            //         'product_id' => $product->id,
            //         'reorder_point' => 0
            //     ]
            // );
            // DB::commit();

            return $product;
        } catch (\Throwable $th) {
            // DB::rollBack();
            throw $th;
        }
    }

    public function transform_display_product($products)
    {
        $products->transform(function ($product) {
            $onhand = 0;
            $delivered = 0;
            $available_for_sale = 0;
            $commited = 0;
            if ($product->stock_management == 1 && $product->bins) {
                foreach ($product->inventory as  $item) {
                    $onhand += $item['onhand'];
                    $delivered += $item['delivered'];
                    $commited += $item['commited'];
                    $available_for_sale += $item['available_for_sale'];
                }
            } elseif ($product->stock_management == 2 && $product->bins) {
                foreach ($product->bins as  $bin) {
                    $onhand += $bin['pivot']['onhand'];
                    $delivered += $bin['pivot']['delivered'];
                    $commited += $bin['pivot']['commited'];
                    $available_for_sale += $bin['pivot']['available_for_sale'];
                }
            }

            $product->available_for_sale = $available_for_sale;
            $product->onhand = $onhand;
            $product->delivered = $delivered;
            $product->commited = $commited;
            if (count($product->skus) > 0) {
                if (count($product->skus) > 1) {
                    $prices = [];
                    $quantity = 0;
                    foreach ($product->skus as  $pro_price) {
                        // dd($pro_price['price']);
                        $prices[] = $pro_price['price'];
                        $quantity += $pro_price['quantity'];
                    }
                    $product->price = 'from ' . min($prices);
                    $product->quantity = $quantity;
                } else {
                    foreach ($product->skus as  $pro_price) {
                        // dd($pro_price['price']);
                        $prices = $pro_price['price'];
                        $quantity = $pro_price['quantity'];
                    }
                    $product->price = $prices;
                    $product->quantity = $quantity;
                }
                // dd(($product->skus[0]->price));
                // $product->quantity = $product->skus[0]->quantity;
            } else {
                $product->price = 0;
                $product->quantity = 0;
            }
            $product->variants = count($product->skus);
            $product->discount = 0;

            return $product;
        });
        return $products;
    }
}
