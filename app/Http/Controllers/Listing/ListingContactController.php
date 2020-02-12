<?php

namespace App\Http\Controllers\Listing;

use Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Http\Requests\StoreListingContactFormRequest;
use App\{Area, Listing};
use App\Mail\ListingContactCreated;

//ALL controller hooked up in web.php
class ListingContactController extends Controller
{

    public function __construct()
    {
      return $this->middleware(['auth']);
    }
    public function store(StoreListingContactFormRequest $request, Area $area, Listing $listing)
    {
      //send the email
      Mail::to($listing->user)->queue(
        new ListingContactCreated($listing, $request->user(), $request->message)
      );

      return back()->withSuccess("We have sent your message to {$listing->user->name}");
    }
}
