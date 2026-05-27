<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\VenueRepository;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity(repositoryClass: VenueRepository::class)]
#[ORM\Table(name: 'venues')]
class Venue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length:225)]
    private string $name;

    #[ORM\Column]
    private int $capacity;

    #[ORM\Column(length:225)]
    private string $city;

    public function __construct(string $name, int $capacity, string $city){
        $this->name = $name;
        $this->capacity = $capacity;
        $this->city = $city;

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

    public function getCapacity(): int {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): void {
        if($capacity < 0){
            $this->capacity = 0;
            return;
        }
        $this->capacity = $capacity;
    }

    public function getCity(): string {
        return $this->city;
    }

    public function setCity(string $city): void {
        $this->city = $city;
    }


    public function toArray() : array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'capacity' => $this->capacity,
            'city' => $this->city
        ];
    }
}
