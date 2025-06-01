<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Mail\UserMail;
use App\Models\Company;
use App\Models\Ingredient;
use App\Models\RecipeIngredient;
use App\Models\Role;
use App\Models\User;
use App\Services\DataTransformService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Illuminate\Support\Str;

class BaseController extends Controller
{
    protected $model;
    protected $json;
    protected $title;
    protected $route;
    protected $image;
    protected $upload;
    protected $actions;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request('axios')) {
            return $this->model->paginate(100);
        }
        $data = $this->model->paginate(100);
        $company = Company::first();

        $jsonFile = public_path('data/' . $this->json); // Get the full path to the JSON file

        $trans = new DataTransformService;
        $jsonData = $trans->data_transform($jsonFile);


        $headers = [];
        $headers[] = ['title' => 'Created At', 'key' => 'created_at'];


        foreach ($jsonData as $item) {
            if ($item['table_display']) {
                $headers[] = [
                    'title' => $item['label'],
                    'key' => $item['model']
                ];
            }
        }
        $headers[] = ['title' => 'Actions', 'key' => 'actions'];



        return Inertia::render('Views/index', [
            'data' => $data,
            'company' => $company,
            'form_data' => $jsonData,
            'headers' => $headers,
            'title' => $this->title,
            'actions' => $this->actions,
            'modelRoute' => $this->route,
            'upload' => $this->upload
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $trans = new DataTransformService;
        $dataValue = $trans->store($request->all());

        // $dataValue['ou_id'] = Auth::user()->ou_id;

        DB::beginTransaction();

        try {
            if ($this->route == 'users') {
                $password = Str::random(8);
                $dataValue['password'] = Hash::make($password);
            }
            $model = $this->model->create($dataValue);


            if ($this->route == 'users') {
                $role = Role::find($dataValue['role_id']);
                $model->assignRole($role);

                $user = User::find($model->id);
                Mail::to($user->email)->send(new UserMail($user, $password));
            }
            DB::commit();
            return redirect()->back()->with('message', 'Created');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    // Helper function to extract ingredient data (quantity and unit)
    private function extractIngredientData($ingredientIds, $transformedData)
    {
        $ingredientData = [];

        foreach ($ingredientIds as $ingredientId) {
            foreach ($transformedData as $item) {
                if ($item['model'] === 'ingredient_id' && $item['value'] == $ingredientId) {
                    $ingredientData[$ingredientId] = [
                        'quantity' => 1, // Assuming 'quantity' is the model name for quantity
                        // 'quantity' => $item['quantity'], // Assuming 'quantity' is the model name for quantity
                        'unit' => 'Units', // Assuming 'unit' is the model name for unit
                    ];
                    break;
                }
            }
        }

        return $ingredientData;
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        $driver_groups = $this->model->find($id);

        $jsonFile = public_path('data/' . $this->json); // Get the full path to the JSON file

        $trans = new DataTransformService;
        return $trans->data_edit_transform($jsonFile, $driver_groups);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $trans = new DataTransformService;
        $dataValue = $trans->store($request->all());

        DB::beginTransaction();

        try {
            $this->model->find($id)->update($dataValue);

            if ($this->route == 'user') {
                $role = Role::find($dataValue['role_id']);
                $this->model->find($id)->assignRole($role);
            }

            DB::commit();
            return redirect()->back()->with('message', 'Updated');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $this->model->find($id)->delete();
        // return redirect()->back()->with('message', 'Deleted');
    }

    public function image(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            if ($request->hasFile('file')) {

                $image = $request->file('file');
                $image_path = $image->store($this->title, 'public');
                $image = "/storage/" . $image_path;

                if ($this->route == 'company') {
                    $this->model->find($id)->update(['logo' => $image]);
                } else {
                    $this->model->find($id)->update(['image' => $image]);
                }
            } else {
                return;
            }

            DB::commit();

            return redirect()->back()->with('message', 'Image uploaded');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
