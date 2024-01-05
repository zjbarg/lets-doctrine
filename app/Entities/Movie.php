<?php

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Ramsey\Uuid\Uuid;

#[Entity(), Table('movies')]
class Movie
{
    #[Id, Column('id', 'guid')]
    private string $id;

    #[Column('title', 'string')]
    private string $title;

    #[Column('year', 'integer')]
    private int $year;

    #[OneToMany('movie', Poster::class, ['all'])]
    private Collection $posters;

    public function __construct(
        string $title,
        int $year,
    ) {
        $this->id = Uuid::uuid7()->toString();

        $this->title = $title;
        $this->year = $year;

        $this->posters = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function createPoster(string $url): Poster
    {
        $this->posters->add($poster = new Poster($this, $url));

        return $poster;
    }

    public function getPosters(): array
    {
        return $this->posters->toArray();
    }
}
