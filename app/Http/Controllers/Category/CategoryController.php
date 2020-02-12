<?php

namespace App\Http\Controllers\Category;

use App\{Area, Category};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index(Area $area)
    {
      //eager load listings
      $categories = Category::withListingsInArea($area)->get()->toTree();

      return view('categories.index', compact('categories'));
    }
}
