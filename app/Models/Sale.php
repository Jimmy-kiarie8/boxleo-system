<?php

namespace App\Models;

use App\Branch;
use App\Scopes\OrderScope;
use App\Seller;
use App\Models\User;
use App\Rider;
use App\Services\SaleService;
use App\Tenant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Log;

class Sale extends Model
{
    use SoftDeletes, Loggable;

    protected $table = 'sales';

    public $with = ['products', 'client'];

    protected $casts = [
        'confirmed' => 'boolean',
        'printed' => 'boolean',
        'paid' => 'boolean',
        'invoiced' => 'boolean'
    ];

    protected $appends = ['tat'];

    public function getHasMultipleOrdersAttribute()
    {
        // Access the client relationship and check if the client has more than one order.
        return $this->client->sales->count() > 1;
    }

    // protected $appends = array('charges');

    // public function getShippingChargesAttribute($value)
    // {
    //     return $charges = Charge::where('sale_id', $this->attributes['id'])->sum('amount');
    // }

    protected $fillable = [
        'drawer_id',
        'client_id',
        'total_price',
        'sub_total',
        'order_no',
        'sku_no',
        'customer_notes',
        'discount',
        'shipping_charges',
        'delivery_date',
        'status',
        'delivery_status',
        'warehouse_id',
        'payment_id',
        'paypal',
        'payment_method',
        'payment_id',
        'terms',
        'template_name',
        'invoiced',
        'is_return_waiting_for_approval',
        'is_salesreturn_allowed',
        'is_test_order',
        'is_emailed',
        'is_dropshipped',
        'is_cancel_item_waiting_for_approval',
        'track_inventory',
        'cancel_notes',
        'confirmed',
        'history_comment',
        'branch_id',
        'mpesa_code',
        'agent_id',
        'seller_id',
        'print_count',
        'cancelled_on',
        'dispatched_on',
        'invoice_value',
        'longitude',
        'latitude',
        'distance',
        'geocoded',
        'print_no',
        'return_date',
        'ou_id',
        'weight',
        'upsell',
        'boxes',
        'loading_no',
        'route',
        'user_id',
        'checkout_id',
        'rider_id',
        'zone_id',
        'platform',
        'courier_id'
    ];

    // public function rider()
    // {
    //     return $this->belongsTo(Rider::class);
    // }

    /**
     * Get all of the transactions for the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'BillRefNumber', 'order_no');
    }

    // public $selectable = [
    //     'total_price', 'sub_total', 'order_no','customer_notes', 'discount', 'shipping_charges', 'delivery_date', 'status', 'delivery_status', 'paypal', 'payment_method', 'payment_id', 'terms', 'template_name','invoiced', 'confirmed'
    // ];

    // protected $appends = ['client_name'];

    // public function getClientNameAttribute()
    // {
    //     if ($this->client) {
    //         return $this->client->name;
    //     }
    // }

    public function dispatch()
    {
        return $this->hasOne(Dispatch::class);
    }

    public function columns()
    {
        $custom = CustomView::where('user_id', Auth::id())->where('app_view', 0)->first();
        // $custom = CustomView::where('user_id', Auth::id())->first();
        if ($custom) {
            $columns = $custom->order;
        } else {
            $columns = Schema::getColumnListing('sales');
            $add = ['created_by', 'client_name', 'client_address', 'client_email', 'client_phone', 'product_name'];
            return array_merge($columns, $add);
        }
        return $columns;
    }
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    /**
     * The users that belong to the role.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('id', 'quantity', 'price', 'sku_no', 'total_price', 'seller_id', 'quantity_sent', 'quantity_delivered', 'quantity_returned', 'quantity_remaining', 'product_rate', 'delivered', 'sent', 'quantity_tobe_delivered');
        // return $this->belongsToMany(Product::class)->using(ProductSale::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get all of the calls for the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls(): HasMany
    {
        return $this->hasMany(Call::class, 'sale_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function drawer()
    {
        return $this->belongsTo(Drawer::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'sale_id');
    }

    public function orderHistory()
    {
        return $this->hasMany(OrderHistory::class, 'sale_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'sale_id');
    }

    public function pod()
    {
        return $this->hasOne(Pod::class, 'sale_id');
    }
    public function label()
    {
        return $this->hasOne(Shippinglabel::class, 'sale_id');
    }

    /**
     * Get all of the documents for the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(SaleDocs::class, 'sale_id');
    }

    /**
     * Get the confirmation associated with the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function confirmation(): HasOne
    {
        return $this->hasOne(OrderConfirm::class, 'sale_id');
    }

    /**
     * Get the agent that owns the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get all of the zones for the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function zones(): BelongsToMany
    {
        return $this->belongsToMany(Zone::class, 'sale_zones')->withPivot('zone_to');
    }

    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        // return Carbon::parse($value)->timezone($timezone)->format('Y-m-d');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        // return Carbon::parse($value)->timezone($timezone)->format('Y-m-d');
        return Carbon::parse($value)->timezone($timezone)->format('Y-m-d');
    }

    public function getDispatchedOnAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('Y-m-d');
    }

    // public function getDeliveryDateAttribute($date)
    // {
    //     if ($date) { 
    //         // $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
    //         return Carbon::parse($date)->timezone('Africa/Nairobi')->format('Y-m-d');
    //     }
    // }

    // public function setTotalPriceAttribute($value)
    // {
    //     // Remove all characters except digits and periods
    //     $cleanedPrice = preg_replace('/[^0-9.]/', '', $value);

    //     // Assign the cleaned value to the "total_price" attribute


    //     $this->attributes['total_price'] = $cleanedPrice;
    // }

    public function setTotalPriceAttribute($value)
    {
        // Remove all characters except digits and periods
        $cleanedPrice = preg_replace('/[^0-9.]/', '', $value);

        $maxValue = 999999.99; // Maximum value for decimal(8,2)
        $scale = 1; // Default scale factor

        // Check if the cleaned price exceeds the maximum value
        while (abs($cleanedPrice) > $maxValue) {
            $cleanedPrice /= 10; // Scale down the price
            $scale *= 10;  // Increase the scale factor
        }

        // Assign the scaled price to the "total_price" attribute
        $this->attributes['total_price'] = $cleanedPrice;

        // Store the scale factor in the "scale" attribute (assuming the column exists in the same table)
        $this->attributes['scale'] = $scale;
    }

    public function getTotalPriceAttribute()
    {
        // Retrieve the stored scaled total price
        $scaledPrice = $this->attributes['total_price'];

        // Retrieve the scale factor (assume 1 if not set)
        $scale = $this->attributes['scale'] ?? 1;

        // Calculate the original total price
        $originalPrice = $scaledPrice * $scale;

        return $originalPrice;
        // return $originalPrice +  (int)$this->attributes['shipping_charges'];
    }

    public function setInvoiceValueAttribute($value)
    {
        // Remove all characters except digits and periods
        $cleanedPrice = preg_replace('/[^0-9.]/', '', $value);

        // Assign the cleaned value to the "total_price" attribute
        $this->attributes['invoice_value'] = $cleanedPrice;
    }

    // public function getOrderNoAttribute($value)
    // {
    //     return format_currency($price);
    // }
    // public function getSubTotalAttribute($price)
    // {
    //     if ((array_key_exists('total_price',$this->attributes))) {
    //         return format_currency($this->attributes['total_price']);
    //     }
    // }

    // public function getUpdatedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format('Y-m-d');
    // }
    // public function getChargesAttribute($price)
    // {
    //     return format_currency($price);
    // }
    // public function getShippingChargesAttribute($price)
    // {
    //     return format_currency($price);
    // }
    // public function getDeliveryDateAttribute($date)
    // {
    //     if ($date) {
    //         return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('Y-m-d');
    //     }
    //     return  null;
    // }

    public function package()
    {
        return $this->hasOne(Package::class, 'sale_id');
    }

    public function returns()
    {
        return $this->hasMany(ReturnSale::class, 'sale_id');
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_sales', 'sale_id', 'branch_id');
    }

    public static function booted()
    {
        // static::addGlobalScope('created_at', function (Builder $builder) {
        //     $builder->orderBy('id', 'DESC');
        // });
        static::addGlobalScope(new OrderScope);
    }

    /**
     * The riders that belong to the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function riders(): BelongsToMany
    {
        return $this->belongsToMany(Rider::class, 'rider_sales', 'rider_id', 'sale_id');
    }

    public function ou()
    {
        return $this->belongsTo(Ou::class, 'company_id');
    }

    /**
     * The services that belong to the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'shipcharges', 'sale_id', 'service_id')->withPivot('amount');
    }

    public function shipcharges()
    {
        return $this->hasMany(Shipcharge::class);
    }

    public function check_limit()
    {
        $tenant = Tenant::setEagerLoads([])->with(['subscriptions' => function ($query) {
            $query->latest()->take(1);
        }])->where('id', tenant('id'))->first();
    }


    public function search($search)
    {
        $data = $search;
        $data_arr = preg_split("/[\r\n,]+/", $data, -1, PREG_SPLIT_NO_EMPTY);
        $data_arr = array_map(function ($a) {
            return str_replace(' ', '', $a);
        }, $data_arr);
        // return Sale::whereIn('order_no', $data_arr)->get();

        if (count($data_arr) > 1) {
            $sales = Sale::with(['user', 'products' => function ($q) {
                $q->setEagerLoads([]);
            }, 'client', 'pod'])
                ->where(function ($query) use ($data_arr) {
                    $query->whereIn('order_no',  $data_arr);
                    $query->orWhereHas('client', function ($q) use ($data_arr) {
                        $q->where(function ($q) use ($data_arr) {
                            $q->whereIn('name', $data_arr);
                            $q->orWhereIn('phone', $data_arr);
                            $q->orWhereIn('address', $data_arr);
                            $q->orWhereIn('email', $data_arr);
                        });
                    });
                    // $query->orWhereHas('products', function ($q) use ($data_arr) {
                    //     $q->where(function ($q) use ($data_arr) {
                    //         $q->whereIn('product_name', $data_arr);
                    //     });
                    // });
                });
        } else {
            $sales = Sale::with(['user', 'products' => function ($q) {
                $q->setEagerLoads([]);
            }, 'client', 'orderHistory' => function ($q) {
                $q->setEagerLoads([]);
            }])
                ->where(function ($query) use ($search) {
                    $query->where('order_no', 'LIKE', '%' . $search . '%');
                    $query->orWhereHas('client', function ($q) use ($search) {
                        $q->where(function ($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%');
                            $q->orWhere('phone', 'LIKE', '%' . $search . '%');
                            $q->orWhere('address', 'LIKE', '%' . $search . '%');
                            $q->orWhere('email', 'LIKE', '%' . $search . '%');
                        });
                    });
                    // $query->orWhereHas('products', function ($q) use ($search) {
                    //     $q->where(function ($q) use ($search) {
                    //         $q->where('product_name', 'LIKE', '%' . $search . '%');
                    //     });
                    // });
                });
        }


        return $sales = $sales->paginate(200);
        // $sale_transform = new Sale;
        // return $sale_transform->trans_sales($sales, '', '');
    }


    public function trans_sales($sales, $trans_type, $count)
    {
        $this->transform_sales($sales);

        // $custom =  Cache::remember('custom_' . Auth::id(), now()->addHours(10), function () {
        //     return CustomView::where('user_id', Auth::id())->where('app_view', false)->first();
        // });


        // $app_view =  Cache::remember('appview_' . Auth::id(), now()->addHours(10), function () {
        //     return CustomView::where('user_id', Auth::id())->where('app_view', true)->first();
        // });

        $custom = CustomView::where('user_id', Auth::id())->where('app_view', false)->first();

        $app_view = CustomView::where('user_id', Auth::id())->where('app_view', true)->first();

        if ($custom && $trans_type == 'custom') {
            $fields = $custom->columns;
            $labels = $custom->labels;

            $merged = collect($labels)->zip($fields)->transform(function ($values) {
                return [
                    'text' => $values[0],
                    'value' => $values[1]
                ];
            });
        }
        // dd($custom, $app_view);
        elseif ($app_view && $trans_type == 'custom') {
            $id = $app_view->id;

            $app_view = new AppCustom;
            return $sales = $app_view->show($id);
        } else {
            $labels = [
                "Created on",
                "Order No.",
                "Client Name",
                "Client Phone",
                "Altenative Phone",
                "Client Address",
                "Client city",
                // "Pickup Phone",
                // "Pickup Address",
                // "Pickup city",
                "Product Name",
                // "Created By",
                "Delivery Status",
                "Status",
                "Delivery Date",
                'Agent',
                "Recall On",
                // "Sub total",
                "Cod amount",
                "Charges",
                'Notes',
                'Return Reasons',
                'Printed',
                'POD',
                'Upsell',
                'Weight',
                "Schedule Date",
                'Checkout ID',
                'Waybill No',
            ];

            $fields = [
                "created_at",
                "order_no",
                "client_name",
                "client_phone",
                "alt_phone",
                "client_address",
                "client_city",
                // "pickup_phone",
                // "pickup_address",
                // "pickup_city",
                "product_name",
                // "created_by",
                "delivery_status",
                "status",
                "delivery_date",
                'agent_name',
                "recall_date",
                // "sub_total",
                // "discount",
                "total_price",
                "shipcharges_sum_amount",
                'customer_notes',
                'return_notes',
                'printed',
                'pod',
                'upsell',
                'weight',
                'schedule_date',
                'checkout_id',
                'waybill_no',
            ];

            if (Auth::user()) {
                array_push($labels, 'Print Count');
                array_push($fields, 'print_count');


                array_push($labels, 'Shipping charges');
                array_push($fields, 'shipping_charges');
            }

            if (Auth::user()) {
                if (Auth::user()->hasRole('Finance')  || Auth::user()->hasRole('Admin')  || Auth::user()->hasRole('Super admin')) {
                    array_push($labels, 'Delivered On');
                    array_push($fields, 'delivered_on');
                    array_push($labels, 'Returned On');
                    array_push($fields, 'returned_on');

                    array_push($labels, 'Invoice Value');
                    array_push($fields, 'invoice_value');
                }
            }


            $merged = collect($labels)->zip($fields)->transform(function ($values) {
                // var_dump($values[1]);
                return [
                    'text' => $values[0],
                    'value' => $values[1]
                ];
            });
        }


        $mpesa_connected =  Cache::remember('appview_' . Auth::id(), now()->addHours(10), function () {
            return Mpesa::exists();
        });
        // $mpesa_connected = Mpesa::exists();

        if ($mpesa_connected) {
            $myObject = new AutoGenerate;
            $myObject->checked = true;
            $myObject->value = "mpesa_code";
            $myObject->text = "M-pesa";
            $merged->push($myObject);
        }


        $myObject = new AutoGenerate;

        $myObject->checked = true;
        $myObject->value = "actions";
        $myObject->text = "Action";
        // $myObject->value = "printed";
        // $myObject->text = "Printed";

        $merged->push($myObject);



        // $merged[] = $myObject;
        // $more = ['actions'];
        // $labels = array_merge($labels, $more);
        // $fields = array_merge($fields, $more);


        return response()->json([
            'sales' => $sales,
            'columns' => $merged,
            'count' => $count
        ], 200);
    }



    public function transform_sales($sales)
    {
        // dd($sales);
        // $charges = Charge::where('sale_id', $this->attributes['id'])->sum('amount');
        $sales->transform(function ($sale) {

            $rider_ex = Rider::where('id', $sale->rider_id)->latest()->select('id', 'name', 'phone')->first();

            if ($rider_ex) {
                // $rider = Rider::find($rider_ex->rider_id);
                $sale->driver_name = $rider_ex->name;
                $sale->driver_phone = $rider_ex->phone;
            }

            // dd($sale->services);
            // $sale->discount = ($sale->discount != null) ? $sale->discount : 0;
            $total = 0;
            $product_name = '';
            $products_count = 0;
            foreach ($sale->products as $product) {
                // dd($product['pivot']['price']);
                $total += $product['pivot']['price'];

                $products_count += $product->pivot->quantity_tobe_delivered;
                $product_name = $product_name . '|' . $product->product_name . ': Qty ' . $products_count;
            }

            // if (Auth::guard('web')->check()) {

            //     foreach ($sale->zones as $zone) {
            //         $sale->zone_to = $zone->pivot->zone_to;
            //         $sale->zone_from = $zone->pivot->zone_id;
            //     }
            // }


            // $sale->order_no = ($sale->seller) ? $sale->seller->order_prefix . $sale->order_no : $sale->order_no;


            // $shipping_charges = 0;
            // foreach ($sale->services as $service) {
            //     $shipping_charges += $service['pivot']['amount'];
            // }
            // $sale->shipping_charges = $shipping_charges;

            $sale->agent_name = ($sale->agent) ? $sale->agent->name : '';

            $sale->product_name = ltrim($product_name, '|');
            $sale->products_count = $products_count;
            $sale->sub_total = $sale->sub_total;
            $sale->total = $total;
            // $sale->total = $sale->sub_total - $sale->discount;
            $sale->client_name = ($sale->client) ? $sale->client->name : 'anonymus';
            $sale->client_phone = ($sale->client) ? $sale->client->phone : '';
            // $sale->alt_phone = ($sale->client) ? $sale->client->phone : '';
            $sale->alt_phone = ($sale->client) ? $sale->client->alt_phone : null;
            $sale->client_email = ($sale->client) ? $sale->client->email : '';
            $sale->client_address = ($sale->client) ? $sale->client->address : '';
            $sale->client_city = ($sale->client) ? $sale->client->city : '';
            // $sale->user_name = $sale->user->name;
            return $sale;
        });
        return $sales;
    }


    public function getTatAttribute()
    {
        if (array_key_exists('delivered_on', $this->attributes)) {
            if (array_key_exists('created_at', $this->attributes)) {

                $endDateTime = $this->attributes['delivered_on'];
                $startDateTime = $this->attributes['created_at'];

                if ($endDateTime) {
                    $startDateTime = Carbon::parse($startDateTime);
                    $endDateTime = Carbon::parse($endDateTime);

                    $differenceInHours = $startDateTime->diffInHours($endDateTime);
                    $differenceInMinutes = $startDateTime->diffInMinutes($endDateTime) % 60;
                    $differenceInSeconds = $startDateTime->diffInSeconds($endDateTime) % 60;

                    return $differenceInHours . 'hrs ' . $differenceInMinutes . 'min ' . $differenceInSeconds . 'sec';
                }
            }
        }

        return null;
    }


    public function clearOrderSearchCache()
    {
        Cache::tags(['order_search'])->flush();
    }


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $order = Sale::where('order_no', $model->order_no)->exists();
            if ($order) {
                return;
                // abort(422, 'Order  ' . $model->order_no . ' exists in our database');
            }
        });

        static::deleting(function ($telco) {
            $relationMethods = ['returns', 'package', 'invoice'];

            foreach ($relationMethods as $relationMethod) {
                if ($telco->$relationMethod()->count() > 0) {
                    // return false;
                    abort(422, 'The order can not be deleted because it contains ' . $relationMethod);
                }
            }
        });


        static::updated(function ($sale) {
            // app(SaleService::class)->clearOrderSearchCache($sale->order_no);
            // app(SaleService::class)->clearOrderScanCache($sale->id);
            app(SaleService::class)->clearOrderCache($sale->id);
        });

        static::deleted(function ($sale) {
            // app(SaleService::class)->clearOrderSearchCache($sale->order_no);
            // app(SaleService::class)->clearOrderScanCache($sale->id);
            app(SaleService::class)->clearOrderCache($sale->id);
        });
    }
}
