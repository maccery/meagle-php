<table class="table">
@foreach ($reviews as $review)
    <tr>
        <td>
            @include('review.small_vote', ['review' => $review])
        </td>
        <td>
            <ul class="list list-unstyled list-inline small">
                <li>"{{ $review->description }}"</li>
                <li><small><a href="{{ route('view_user', $review->author->id) }}">{{ $review->author->name }}</a></small></li>
                <li><small>{{ $review->author->votes->sum('vote') }} points</small></li>
            </ul>
        </td>
    </tr>
@endforeach
</table>