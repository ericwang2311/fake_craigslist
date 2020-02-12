<?php

namespace App\Http\Controllers\Listing;

use App\Area;
use App\Listing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListingFavouriteController extends Controller
{
    //UPDATE in web.php
    public function __construct()
    {
        $this->middleware(['auth']); //protects all middleware routes to any method in this controller
    }

    public function index(Request $request) //set up in routes
    {
        $listings = $request->user()->favouriteListings()->with(['user', 'area'])->paginate(10);

        return view('user.listings.favourites.index', compact('listings'));
    }

    public function store(Request $request, Area $area, Listing $listing)
    {
        $request->user()->favouriteListings()->syncWithoutDetaching([$listing->id]);

        return back()->withSuccess('Listing added to favourites.');
    }
    public function destroy(Request $request, Area $area, Listing $listing)//takes a request, gets a listing from the area
    {
        $request->user()->favouriteListings()->detach($listing);

        return back()->withSuccess('Listing removed from favourites.');
    }
}
