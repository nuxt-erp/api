<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Inventory\Entities\Product as EntitiesProduct;

class DashboardController extends Controller
{
    use ResponseTrait;

    public function index(){

        $result = [
            'products'  => EntitiesProduct::count(),
            //'recipes'   => Recipe::count()
        ];

        return $this->sendArray($result);
    }

    public function welcome(){
        echo '<==== NextERP API ====>';
    }

}
