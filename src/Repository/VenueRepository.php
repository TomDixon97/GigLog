<?php
declare (strict_types = 1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Venue;

class VenueRepository extends EntityRepository{

    public function save(Venue $venue) : void {
        $em = $this->getEntityManager();
        $em->persist($venue);
        $em->flush();
    }

    public function remove(Venue $venue) : void {
        $em = $this->getEntityManager();
        $em->remove($venue);
        $em->flush();
    }
}