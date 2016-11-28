<table class="table">
@foreach ($reviews as $review)
    <tr>
        <td>
            @include('review.vote', ['review' => $review])
        </td>
        <td>
            <h3>"{{ $review->description }}"</h3>
            <div class="pull-right">
                <ul class="list list-unstyled list-inline small">
                    <li><a href="{{ route('view_user', $review->author->id) }}">{{ $review->author->name }}</a></li>
                    <li>{{ $review->author->votes->sum('vote') }} points</li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
</table>