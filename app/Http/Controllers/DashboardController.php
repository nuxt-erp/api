<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    use ResponseTrait;

    public function index(){

        $result = [
            'products'  => Product::count(),
            'recipes'   => Recipe::count()
        ];

        return $this->respondWithArray($result);
    }

}
