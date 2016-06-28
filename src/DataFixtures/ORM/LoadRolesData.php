<?php

namespace Zantolov\AppBundle\DataFixtures\ORM;

use Zantolov\AppBundle\DataFixtures\ORM\AbstractDbFixture;
use Zantolov\AppBundle\Entity\Role;
use Zantolov\AppBundle\Entity\User;

class LoadRolesData extends AbstractDbFixture
{

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        foreach (['ROLE_ADMIN', 'ROLE_USER'] as $roleName) {
            $role = new Role();
            $role->name = $roleName;
            $manager->persist($role);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

}
