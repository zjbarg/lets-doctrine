<?php

namespace Unit\Movies;

use App\Entities\Movie;
use App\Entities\Poster;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PosterTest extends TestCase
{
    public function test_usesGivenUrlForImage(): void
    {
        $movie = new Movie('title', '2020');

        $poster = new Poster($movie, 'my-url');

        $this->assertEquals('my-url', $poster->getUrl());
    }

    public function test_getMovieReturnsGivenMovie(): void
    {
        $movie = new Movie('title', '2020');

        $poster = new Poster($movie, 'my-url');

        $this->assertEquals($movie, $poster->getMovie());
    }

    public function test_getIdReturnsValidUuid(): void
    {
        $movie = new Movie('title', '2020');

        $poster = new Poster($movie, 'my-url');

        $this->assertTrue(Uuid::isValid($poster->getId()));
    }
}
