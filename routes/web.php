<?php

use App\Entities\Movie;
use App\Entities\Poster;
use Illuminate\Support\Facades\Route;
use LaravelDoctrine\ORM\Facades\EntityManager;
use Faker\Factory as Faker;
use Illuminate\Http\Request;

$faker = Faker::create();

$movieToArray = function (Movie $movie) {
    return [
        'id' => $movie->getId(),
        'title' => $movie->getTitle(),
        'year' => $movie->getYear(),
    ];
};

$posterToArray = function (Poster $poster) {
    return [
        'id' => $poster->getId(),
        'url' => $poster->getUrl(),
        'movie_id' => $poster->getMovie()->getId(),
    ];
};

Route::get('movies/new-random', function () use ($faker, $movieToArray) {
    $movie = new Movie(
        $faker->name,
        $faker->year,
    );

    EntityManager::persist($movie);
    EntityManager::flush();

    return $movieToArray($movie);
});

Route::get('movies/{movie_id}', function ($movie_id) use ($faker, $movieToArray) {
    $movie = EntityManager::find(Movie::class, $movie_id);

    if (is_null($movie)) {
        abort(404);
    }

    return $movieToArray($movie);
});

Route::get('movies', function () use ($movieToArray) {
    $movies =  EntityManager
        ::createQuery('select movie from App\Entities\Movie movie')
        ->execute();

    return array_map($movieToArray, $movies);
});


Route::get('movies/{movie_id}/posters', function ($movie_id) use ($posterToArray) {
    $posters =  EntityManager
        ::createQuery('select poster from App\Entities\Poster poster where poster.movie = :movie_id')
        ->setParameter('movie_id', $movie_id)
        ->execute();

    return array_map($posterToArray, $posters);
});


// use like /posters?movie=uuid
Route::get('posters', function (Request $request) use ($posterToArray) {

    $query_builder = EntityManager
        ::createQueryBuilder()
        ->select('poster')
        ->from(Poster::class, 'poster');

    if ($request->input('movie')) {
        $query_builder->where('poster.movie = :movie');
        $query_builder->setParameter('movie', $request->input('movie'));
    }

    $posters = $query_builder->getQuery()->execute();

    return array_map($posterToArray, $posters);
});


Route::get('movies/{movie_id}/posters/new-random', function ($movie_id) use ($faker, $posterToArray) {

    /** @var Movie|null */
    $movie = EntityManager::find(Movie::class, $movie_id);

    if (is_null($movie)) {
        abort(404);
    }

    $poster = $movie->createPoster($faker->imageUrl());

    EntityManager::flush();

    return $posterToArray($poster);
});
