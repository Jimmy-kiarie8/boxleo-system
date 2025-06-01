<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Driver;
use App\Models\Merchant;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Sale;
use App\Models\Ou;
use App\Models\Product;
use App\Rider;
use App\Seller;

class DataTransformService
{

    public function data_transform($jsonFile)
    {

        if (file_exists($jsonFile)) {
            $jsonContents = file_get_contents($jsonFile);
            $jsonData = json_decode($jsonContents, true);

            $fetchClients = false;
            $fetchSales = false;
            $fetchProduct = false;
            $fetchDriver = false;
            $fetchMerchant = false;
            $fetchPermission = false;
            $fetchRole = false;
            $fetchOu = false;

            // Check if client_id or sale_id is present in the JSON data
            foreach ($jsonData as $item) {
                if ($item['type'] == 'select') {
                    if ($item['model'] == 'client_id') {
                        $fetchClients = true;
                    } elseif ($item['model'] == 'sale_id') {
                        $fetchSales = true;
                    } elseif ($item['model'] == 'product_id') {
                        $fetchProduct = true;
                    } elseif ($item['model'] == 'driver_id') {
                        $fetchDriver = true;
                    } elseif ($item['model'] == 'seller_id') {
                        $fetchMerchant = true;
                    } elseif ($item['model'] == 'permission_id') {
                        $fetchPermission = true;
                    } elseif ($item['model'] == 'role_id') {
                        $fetchRole = true;
                    } elseif ($item['model'] == 'ou_id') {
                        $fetchOu = true;
                    }
                }
            }

            // Fetch data conditionally
            if ($fetchClients) {
                $clients = Client::select('id', 'name')->take(100)->get();
            }
            if ($fetchSales) {
                $sales = Sale::select('id', 'order_no')->take(100)->get();
            }
            if ($fetchProduct) {
                $product = Product::select('id', 'product_name')->take(10)->get();
            }
            if ($fetchDriver) {
                $drivers = Rider::select('id', 'name')->take(100)->get();
            }
            if ($fetchMerchant) {
                $merchants = Seller::select('id', 'name')->take(100)->get();
            }
            if ($fetchPermission) {
                $permissions = Permission::select('id', 'name')->take(100)->get();
            }
            if ($fetchRole) {
                $roles = Role::select('id', 'name')->take(100)->get();
            }
            if ($fetchOu) {
                $ous = Ou::select('id', 'ou')->take(100)->get();
            }

            // Rest of the logic for transformation
            foreach ($jsonData as $key => &$item) {


                if ($fetchClients && $item['type'] == 'select' && $item['model'] == 'client_id') {
                    $item['items'] = $clients;

                    foreach ($item['items'] as &$item) {
                        $item['value'] = $item['id'];
                        $item['label'] = $item['name'];
                    }
                } elseif ($fetchSales && $item['type'] == 'select' && $item['model'] == 'sale_id') {
                    $item['items'] = $sales;

                    foreach ($item['items'] as &$item) {
                        $item['value'] = $item['id'];
                        $item['label'] = $item['order_no'];
                    }
                } elseif ($fetchProduct && $item['type'] == 'select' && $item['model'] == 'product_id') {
                    $item['items'] = $product;

                    foreach ($item['items'] as &$item) {
                        $item['value'] = $item['id'];
                        $item['label'] = $item['product_name'];
                    }
                } elseif ($fetchDriver && $item['type'] == 'select' && $item['model'] == 'driver_id') {
                    $item['items'] = $drivers;

                    foreach ($item['items'] as &$item) {
                        $item['value'] = $item['id'];
                        $item['label'] = $item['name'];
                    }
                } elseif ($fetchMerchant && $item['type'] == 'select' && $item['model'] == 'seller_id') {
                    $item['items'] = $merchants;


                    foreach ($item['items'] as &$item) {
                        $item['value'] = $item['id'];
                        $item['label'] = $item['name'];
                    }
                } elseif ($fetchPermission && $item['type'] == 'select' && $item['model'] == 'permission_id') {
                    $item['items'] = $permissions;


                    foreach ($item['items'] as &$item) {
                        $item['value'] = $item['id'];
                        $item['label'] = $item['name'];
                    }
                } elseif ($fetchRole && $item['type'] == 'select' && $item['model'] == 'role_id') {
                    $item['items'] = $roles;


                    foreach ($item['items'] as &$item) {
                        $item['value'] = $item['id'];
                        $item['label'] = $item['name'];
                    }
                } elseif ($fetchOu && $item['type'] == 'select' && $item['model'] == 'ou_id') {
                    $item['items'] = $ous;


                    foreach ($item['items'] as &$item) {
                        $item['value'] = $item['id'];
                        $item['label'] = $item['ou'];
                    }
                }
            }
        } else {
            return response('JSON file not found', 404);
        }

        return $jsonData;
    }

    public function getHeaders($jsonData, $headers)
    {

        foreach ($jsonData as $item) {
            if ($item['table_display']) {
                $headers[] = [
                    'title' => $item['label'],
                    'key' => $item['model']
                ];
            }
        }
        return $headers;
    }

    public function store($data)
    {
        $dataValue = [];
        foreach ($data as $item) {

            $model = $item['model'];
            if ($item['type']  == 'radio') {
                $value = ($item['value'] == 'Yes') ? true : false;
            } else {
                $value = $item['value'];
            }

            $dataValue[$model] = $value;
        }
        return $dataValue;
    }

    public function data_edit_transform($jsonFile, $data)
    {
        if (file_exists($jsonFile)) {
            $jsonContents = file_get_contents($jsonFile);
            $jsonData = json_decode($jsonContents, true);

            $fetchClients = false;
            $fetchSales = false;
            $fetchProduct = false;
            $fetchDriver = false;
            $fetchDriver = false;
            $fetchMerchant = false;
            $fetchPermission = false;
            $fetchRole = false;

            // Check if client_id or sale_id is present in the JSON data
            foreach ($jsonData as $item) {
                if ($item['type'] == 'select') {
                    if ($item['model'] == 'client_id') {
                        $fetchClients = true;
                    } elseif ($item['model'] == 'sale_id') {
                        $fetchSales = true;
                    } elseif ($item['model'] == 'product_id') {
                        $fetchProduct = true;
                    } elseif ($item['model'] == 'driver_id') {
                        $fetchDriver = true;
                    } elseif ($item['model'] == 'seller_id') {
                        $fetchMerchant = true;
                    } elseif ($item['model'] == 'permission_id') {
                        $fetchPermission = true;
                    } elseif ($item['model'] == 'role_id') {
                        $fetchRole = true;
                    }
                }
            }

            // Fetch data conditionally
            if ($fetchClients) {
                $clients = Client::select('id', 'name')->take(100)->get();
            }
            if ($fetchSales) {
                $sales = Sale::select('id', 'order_no')->take(100)->get();
            }
            if ($fetchProduct) {
                $product = Product::select('id', 'name')->take(100)->get();
            }
            if ($fetchDriver) {
                $drivers = Driver::select('id', 'name')->take(100)->get();
            }
            if ($fetchMerchant) {
                $merchants = Seller::select('id', 'name')->take(100)->get();
            }
            if ($fetchPermission) {
                $permissions = Permission::select('id', 'name')->take(100)->get();
            }
            if ($fetchRole) {
                $roles = Role::select('id', 'name')->take(100)->get();
            }

            // Rest of the logic for transformation
            foreach ($jsonData as $key => &$item) {

                // $model = $data->{$item['model']);
                $model = $data->{$item['model']};

                if ($item['type'] == 'radio') {
                    $item['value'] = ($model) ? 'Yes' : 'No';
                } else {

                    if ($fetchClients && $item['type'] == 'select' && $item['model'] == 'client_id') {
                        $item['items'] = $clients;
                        $item['value'] = $model;

                        foreach ($item['items'] as &$item) {
                            $item['value'] = $item['id'];
                            $item['label'] = $item['name'];
                        }
                    } elseif ($fetchSales && $item['type'] == 'select' && $item['model'] == 'sale_id') {
                        $item['items'] = $sales;
                        $item['value'] = $model;

                        foreach ($item['items'] as &$item) {
                            $item['value'] = $item['id'];
                            $item['label'] = $item['order_no'];
                        }
                    } elseif ($fetchProduct && $item['type'] == 'select' && $item['model'] == 'product_id') {
                        $item['items'] = $product;
                        $item['value'] = $model;

                        foreach ($item['items'] as &$item) {
                            $item['value'] = $item['id'];
                            $item['label'] = $item['name'];
                        }
                    } elseif ($fetchDriver && $item['type'] == 'select' && $item['model'] == 'driver_id') {
                        $item['items'] = $drivers;
                        $item['value'] = $model;

                        foreach ($item['items'] as &$item) {
                            $item['value'] = $item['id'];
                            $item['label'] = $item['name'];
                        }
                    } elseif ($fetchMerchant && $item['type'] == 'select' && $item['model'] == 'seller_id') {
                        $item['items'] = $merchants;
                        $item['value'] = $model;


                        foreach ($item['items'] as &$item) {
                            $item['value'] = $item['id'];
                            $item['label'] = $item['name'];
                        }
                    } elseif ($fetchPermission && $item['type'] == 'select' && $item['model'] == 'permission_id') {
                        $item['items'] = $permissions;
                        $item['value'] = $model;


                        foreach ($item['items'] as &$item) {
                            $item['value'] = $item['id'];
                            $item['label'] = $item['name'];
                        }
                    } elseif ($fetchRole && $item['type'] == 'select' && $item['model'] == 'role_id') {
                        $item['items'] = $roles;
                        $item['value'] = $model;


                        foreach ($item['items'] as &$item) {
                            $item['value'] = $item['id'];
                            $item['label'] = $item['name'];
                        }
                    }
                $item['value'] = $model;
            }



            }
        } else {
            return response('JSON file not found', 404);
        }

        return $jsonData;
    }
}
