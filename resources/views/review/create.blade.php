@if($current_version->canLeaveReview())
    @if (Auth::User())
        <h3>{{ Auth::User()->name }}, have your say</h3>
    @endif
    <form method="POST" action="{{ route('post_review') }}">
        <div class="form-group">
            <label for="title">Summarise your review</label>
            <input type="hidden" name="version_id" value="{{ $current_version->id }}">
            <input class="form-control" id="title" name="title" class="input-group input-lg" placeholder="Review title">
        </div>
        <div class="form-group">
            <label for="description">Review body</label>
            <textarea class="form-control" rows="8" id="description" name="description" class="input-group input-lg" placeholder="Your review here"></textarea>
        </div>
        <div class="form-group">
            <label for="title">What didn't you like?</label> Separate with commas
            <input class="form-control" name="negative" class="input-group input-lg" placeholder="Negative tags">
        </div>
        <div class="form-group">
            <label for="title">What did you like?</label> Separate with commas
            <input class="form-control" name="positive" class="input-group input-lg" placeholder="Positive tags">
        </div>
    @if (Auth::guest())
        <p><small><a href="{{ url('/register') }}">Register</a> to submit</small></p>
    @else
        <p><small>Submit as <b>{{ Auth::user()->name }}</b></small></p>
        <button class="btn btn-default">Review</button>
    @endif
        {{ csrf_field() }}
    </form>
    @include('errors.generic')
@endif
