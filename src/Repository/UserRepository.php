<?php
namespace Zantolov\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

use Zantolov\AppBundle\Entity\User;

class UserRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getAllAdmin(){
        $query = $this->getEntityManager()
            ->createQuery('SELECT u FROM ZantolovAppBundle:User u WHERE u.roles LIKE :role')
            ->setParameter('role', '%"ROLE_SUPER_ADMIN"%');
        return $query->getResult();
    }

}