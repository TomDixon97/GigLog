<?php

declare(strict_types=1);

namespace App\Repositories;
use App\Entity\Gig;
use Doctrine\ORM\EntityRepository;

class GigRepository extends EntityRepository{
    public function save (Gig $gig) : void{
        $em = $this->getEntityManager();
        $em->persist($gig);
        $em->flush();

    }
    public function remove (Gig $gig) : void{
        $em = $this->getEntityManager();
        $em->remove($gig);
        $em->flush();

    }

}