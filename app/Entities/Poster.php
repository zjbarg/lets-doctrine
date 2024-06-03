<?php

namespace App\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Ramsey\Uuid\Uuid;

#[Entity(), Table('posters')]
class Poster
{
    #[Id, Column('id', 'guid')]
    private string $id;

    #[ManyToOne(Movie::class, ['all']), JoinColumn('movie_id')]
    private Movie $movie;

    #[Column('url')]
    private string $image_url;

    public function __construct(
        Movie $movie,
        string $url
    ) {
        $this->id = Uuid::uuid7();
        $this->movie = $movie;
        $this->image_url = $url;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMovie(): Movie
    {
        return $this->movie;
    }

    public function getUrl(): string
    {
        return $this->image_url;
    }
}
