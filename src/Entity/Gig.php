<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\GigRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GigRepository::class)]
#[ORM\Table(name: 'gigs')]
class Gig{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\ManyToOne(targetEntity: Venue::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Venue $venue;
    #[ORM\Column(type: 'date_immutable')]
    private DateTimeImmutable $date;

    #[ORM\ManyToMany(targetEntity: Artist::class)]
    #[ORM\JoinTable(name: 'gig_artists')]
    private Collection $artists;

    public function __construct(Venue $venue, DateTimeImmutable $date){
        $this->venue = $venue;
        $this->date = $date;
        $this->artists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVenue(): Venue
    {
        return $this->venue;
    }

    public function setVenue(Venue $venue): void
    {
        $this->venue = $venue;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): void
    {
        $this->rating = $rating;
    }

    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): void
    {
        // Avoid duplicates — only add if not already in the collection.
        if (!$this->artists->contains($artist)) {
            $this->artists->add($artist);
        }
    }

    public function removeArtist(Artist $artist): void
    {
        $this->artists->removeElement($artist);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d'),
            'rating' => $this->rating,
            'venue' => $this->venue->toArray(),
            // Map each artist in the collection to its array form.
            'artists' => array_map(
                fn(Artist $a) => $a->toArray(),
                $this->artists->toArray(),
            ),
        ];
    }



}