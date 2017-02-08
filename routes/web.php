<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Software;
use Illuminate\Http\Request;

Route::group(['middleware' => 'web'], function() {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::post('/search', 'PostSearchController@search')->name('search');

    Route::get('/review/{review}', function (App\Review $review) {
        return view('review/index', ['review' => $review]);
    })->name('review');

    Route::get('/browse', function () {
        $softwares = Software::all();
        return view('browse/index', ['softwares' => $softwares]);
    })->name('browse');

    Route::get('/browse/{software}', function (App\Software $software) {
        $reviews = $software->reviews->sortByDesc(function ($reviews) {
            return $reviews->votes->sum('vote');
        });
        $versions = $software->versions->where('confirmed_real', true);

        return view('browse/view', ['software' => $software, 'reviews' => $reviews, 'versions' => $versions]);
    })->name('browse_name');

    Route::get('/browse/{software}/{version}', function (App\Software $software, App\Version $version) {
        $reviews = $version->reviews->sortByDesc(function ($reviews) {
            return $reviews->votes->sum('vote');
        });
        
        $versions = $software->versions->where('confirmed_real', true);

        return view('browse/view', [
            'software' => $software,
            'reviews' => $reviews,
            'versions' => $versions,
            'version_id' => $version->id,
            'current_version' => $version
        ]);
    })->name('browse_by_version');

    Route::get('/unconfirmed/{software}', function (App\Software $software) {
        $reviews = $software->reviews->sortByDesc(function ($reviews) {
            return $reviews->votes->sum('vote');
        });
        $versions = $software->versions;

        return view('browse/view', ['software' => $software, 'reviews' => $reviews, 'versions' => $versions]);
    })->name('unconfirmed_versions');

    Route::get('/suggest/{version}', function (App\Version $version) {
        return view('browse/suggest_date', ['version' => $version]);
    })->name('suggest_dates');

    Route::get('/create_version/{software}', function (App\Software $software) {
        return view('browse/create_version', ['software' => $software]);
    })->name('create_version');

    Route::get('/user/{user}', function (App\User $user) {
        return view('user/view', ['user' => $user]);
    })->name('view_user');


    Route::post('/post', 'PostController@store')->middleware('auth')->name('post_review');
    Route::post('/post_suggest_date', 'PostSuggestDateController@store')->middleware('auth')->name('post_suggest_date');
    Route::post('/post_create_verison',
        'PostCreateVersionController@store')->middleware('auth')->name('post_create_version');

    Route::get('/vote/{review}/{vote}', function (App\Review $review, $vote, Request $request) {
        $keys = ['review_id' => $review->id, 'user_id' => $request->user()->id];

        $current_vote = App\Vote::where($keys)->first();
        if (isset($current_vote)) {
            $current_vote->delete();
        }
        if (!isset($current_vote) or isset($current_vote) and $current_vote->vote != $vote) {
            App\Vote::updateOrCreate($keys, [
                'vote' => $vote,
            ]);
        }

        return back();
    })->middleware('auth')->name('vote_review');

    Route::get('/vote_version/{version}/{vote}', function (App\Version $version, $vote, Request $request) {
        $keys = ['version_id' => $version->id, 'user_id' => $request->user()->id];

        $current_vote = App\VersionVote::where($keys)->first();
        if (isset($current_vote)) {
            $current_vote->delete();
        }
        if (!isset($current_vote) or isset($current_vote) and $current_vote->vote != $vote) {
            App\VersionVote::updateOrCreate($keys, [
                'vote' => $vote,
            ]);
        }

        return back();
    })->middleware('auth')->name('vote_version');

    Route::get('/vote_suggested_date/{suggested_release_date}/{vote}',
        function (App\SuggestedReleaseDate $suggested_release_date, $vote, Request $request) {
            $keys = [
                'user_id' => $request->user()->id,
                'suggested_release_date_id' => $suggested_release_date->id,
            ];
            $current_vote = App\SuggestedReleaseDateVote::where($keys)->first();
            if (isset($current_vote)) {
                $current_vote->delete();
            }
            if (!isset($current_vote) or isset($current_vote) and $current_vote->vote != $vote) {
                App\SuggestedReleaseDateVote::updateOrCreate($keys, [
                    'vote' => $vote,
                ]);
            }

            return back();
        })->middleware('auth')->name('vote_suggested_date');

    Auth::routes();

    Route::get('/home', 'HomeController@index');
});