<?php
declare(strict_types=1);

namespace App\Repository;



use App\Entity\Artist;
use Doctrine\ORM\EntityRepository;


class ArtistRepository extends EntityRepository {

    public function save(Artist $artist) : void {
        $em = $this->getEntityManager();
        $em->persist($artist);
        $em->flush();
    }


    public function remove(Artist $artist) : void {
        $em = $this->getEntityManager();
        $em->remove($artist);
        $em->flush();
    }

}