<?php

namespace App\Http\Controllers\Listing;

use App\Area;
use App\Category;
use App\Listing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Jobs\UserViewedListing;
use App\Http\Requests\StoreListingFormRequest;

class ListingController extends Controller
{
    public function index(Area $area, Category $category)
    {
        $listings = Listing::with(['user', 'area'])->isLive()->inArea($area)->fromCategory($category)->latestFirst()->paginate(10); //paginate is links per page

        //dd($listings->get());
        return view('listings.index', compact('listings', 'category'));
    }

    public function show(Request $request, Area $area, Listing $listing)
    {
        if (!$listing->live()) {
            abort(404);
        }

        //log view
      if ($request->user()) { //this makes sure only SIGNED IN people can see
           dispatch(new UserViewedListing($request->user(), $listing));
      }

        return view('listings.show', compact('listing'));
    }

    public function create()
    {
        return view('listings.create');
    }

    public function store(StoreListingFormRequest $request, Area $area)
    {
        $listing = new Listing;
        $listing->title = $request->title;//title is from the request as we submit the form title
        $listing->body = $request->body;
        $listing->category_id = $request->category_id;
        $listing->area_id = $request->area_id;
        $listing->user()->associate($request->user());
        $listing->live = false;//THIS

        $listing->save();

        //redirect to the edit page
        return redirect()->route('listings.edit', [$area, $listing]);
    }

    public function edit(Request $request, Area $area, Listing $listing)
    {
        $this->authorize('edit', $listing);//can the signed in user edit a listing?
        return view('listings.edit', compact('listing'));
    }

    public function update(StoreListingFormRequest $request, Area $area, Listing $listing)
    {
        $this->authorize('update', $listing);

        $listing->title = $request->title;
        $listing->body = $request->body;

        if (!$listing->live()) {
            $listing->category_id = $request->category_id;
        }

        $listing->area_id = $request->area_id;

        $listing->save();

        //check if payment has been clicked
        if ($request->has('payment')) {
            return redirect()->route('listings.payment.show', [$area,$listing]);
        }

        return back()->withSuccess('Listing edited successfully.');
    }

    public function destroy(Area $area, Listing $listing)
    {
        //authorize
        $this->authorize('destroy', $listing);
        //delete
        $listing->delete();
        //redirect
        return back()->withSuccess('Listing was deleted.');
    }
}
