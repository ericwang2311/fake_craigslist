<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\{User, Listing};

class UserViewedListing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $user; //these allow the queue to work with the data

    public $listing;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Listing $listing)
    {
        $this->user = $user;
        $this->listing = $listing;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $viewed = $this->user->viewedListings;//all of the user's view listings

        if($viewed->contains($this->listing)){
           $viewed->where('id', $this->listing->id)->first()->pivot->increment('count');//viewed where this id matches the listing id THEN grab 1st table, access pivot, and increment
           return;
        }

        $this->user->viewedListings()->attach($this->listing, [
          'count' => 1
        ]);
        //if we already have a view on listing
        //if yes, increment +1
        //insert initial record
    }
}
