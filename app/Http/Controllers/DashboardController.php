<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Config;
use App\Models\User;
use App\Resources\UserResource;
use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DashboardController extends ControllerService
{
    use ResponseTrait;

    public function test(){

        $companies = Company::all();

        foreach ($companies as $company) {
            config(['database.connections.tenant.schema' => $company->schema]);
            DB::reconnect('tenant');
            DB::transaction(function () {

                $config = Config::find(1);
                if (!empty($config) && !empty($config->shopify_sync_sales) && $config->shopify_sync_sales === true) {
                    $api = resolve('Shopify\API');
                    $api->syncOrders();
                }
            });
        }
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request){

        $result = [
            'products'  => Product::count(),
            //'recipes'   => Recipe::count()
        ];

        return $this->sendArray($result);
    }

    public function welcome(){
        echo '<==== NextERP API ====>';

        // $api    = resolve('Shopify\API');
        // $api->syncOrders();
    }

    public function updateProfile(Request $request){

        $user = auth()->user();

        if($user) {
            $rule = Rule::unique('users')->ignore($user->id);

            $validatorResponse = $this->validateRequest($request, null, [
                'email' => [
                    'max:255',
                    'email',
                    $rule,
                ]
            ]);

            if (!empty($validatorResponse)) {
                return $this->validationResponse($validatorResponse);
            }

            $data = [
                'name'  => $request->input('name') ?? $user->name,
                'email' => $request->input('email') ?? $user->email,
            ];

            if ($request->has('password') && !empty($request->input('password'))){
                $data['password'] =  bcrypt($request->input('password'));
            }

            $user = User::updateOrCreate(
                ['id' => $user->id],
                $data
            );

            return $this->sendObjectResource($user, UserResource::class);

        }

        return $this->notFoundResponse([]);


    }

}
