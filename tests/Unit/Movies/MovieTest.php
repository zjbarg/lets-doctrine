<?php

namespace Unit\Movies;

use App\Entities\Movie;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class MovieTest extends TestCase
{
    public function testInstantiatesMovieWithTitleAndYear(): void
    {
        $movie = new Movie('my-movie', 2020);

        $this->assertEquals('my-movie', $movie->getTitle());
        $this->assertEquals(2020, $movie->getYear());
    }

    public function testNewMovieshaveNoPosters(): void
    {
        $movie = new Movie('my-movie', 2020);

        $this->assertEmpty($movie->getPosters());
    }

    public function testNewMovieHasUuidForId(): void
    {
        $movie = new Movie('my-movie', 2020);

        $this->assertTrue(Uuid::isValid($movie->getId()));
    }

    public function test_createPosterCreatesPosterFromUrl(): void
    {
        $movie = new Movie('my-movie', 2020);

        $poster = $movie->createPoster('my-url');

        $this->assertEquals('my-url', $poster->getUrl());
        $this->assertEquals($movie, $poster->getMovie());
    }

    public function test_createPosterAddsPosterToMoviePostersList(): void
    {
        $movie = new Movie('my-movie', 2020);

        $poster = $movie->createPoster('my-url');

        $this->assertContains($poster, $movie->getPosters());
    }

    public function test_canAddMultiplePosters(): void
    {
        $movie = new Movie('my-movie', 2020);

        $movie->createPoster('my-url');
        $movie->createPoster('my-other-url');


        $this->assertCount(2, $movie->getPosters());
    }
}
