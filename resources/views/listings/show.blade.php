@extends('layouts.app')

@section('content')
<div class="row">
    @if (Auth::check())
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="panel-body">
                        <li><a href="{{ route('listings.share.index', [$area, $listing]) }}">Email to a friend</a></li>

                        @if(!$listing->favouritedBy(Auth::user()))
                        <li><a href="{{ route('listings.favourites.store', [$area, $listing]) }}">Add to favourites</a></li>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="{{ Auth::check() ? 'col-md-9' : 'col-md-12' }}">


        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>{{ $listing->title }} in <span class="text-muted">{{ $listing->area->name }}</span></h4>
            </div>
            <div class="panel-body">
                {!! nl2br(e($listing->body)) !!}
                <hr>
                <p>Viewed {{ $listing->views() }} times</p>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                Contact {{ $listing->user->name }}
            </div>
            <div class="panel-body">
                @if(Auth::guest())
                <p><a href="/register">Sign up</a> for an account or <a href="/login">sign in</a> to contact listing owners.</p>
                @else
                <form action="{{ route('listings.contact.store', [$area, $listing]) }}" method="post">
                    <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                        <label for="message" class="control-label">Message</label>
                        <textarea name="message" id="message" cols="30" rows="5" class="form-control"></textarea>

                        @if($errors->has('message'))
                            <span class="help-block">
                                {{ $errors->first('message') }}
                            </span>
                            @endif

                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-default">Send</button>
                        <span class="help-block">
                            This will email {{ $listing->user->name }} and they'll be able to reply directly to you by email.
                        </span>
                    </div>

                    {{ csrf_field() }}
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
