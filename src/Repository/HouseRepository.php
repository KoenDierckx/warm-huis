<?php

namespace One\CheckJeHuis\Repository;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;
use One\CheckJeHuis\Entity\City;
use One\CheckJeHuis\Entity\House;

class HouseRepository extends EntityRepository
{
    public function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    public function getHousesFromCity(City $city)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('h');
        $qb->from(House::class, 'h');
        $qb->where('h.city = :city');
        $qb->setParameter('city', $city);
        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function removeHouse($house)
    {
        $em = $this->getEntityManager();
        $em->remove($house);
        $em->flush();
    }
}
