<?php
declare(strict_types=1);

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArtistRepository;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
#[ORM\Table(name: 'artists')]
class Artist{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $genre = null;

    public function __construct(string $name, ?string $genre = null){
        $this->name = $name;
        $this->genre = $genre;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): void
    {
        $this->genre = $genre;
    }

    public function toArray(): array {
        return ["id" => $this->id, "name" => $this->name];
    }

}