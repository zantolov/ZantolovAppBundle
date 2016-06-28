<?php

namespace Zantolov\AppBundle\DataFixtures\ORM;

use Zantolov\AppBundle\DataFixtures\ORM\AbstractDbFixture;
use Zantolov\AppBundle\Entity\User;

class LoadUserData extends AbstractDbFixture
{

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $userManager = $this->container->get('fos_user.user_manager');

        /** @var User $user */
        $user = $userManager->createUser();
        $user->setUsername('user');
        $user->setEmail('user@mailinator.com');
        $user->setPlainPassword('123456');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_USER'));
        $this->addReference('user0', $user);
        $userManager->updateUser($user, true);

        $user = $userManager->createUser();
        $user->setUsername('admin');
        $user->setEmail('admin@mailinator.com');
        $user->setPlainPassword('123456');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_USER', 'ROLE_ADMIN'));
        $this->addReference('admin', $user);
        $userManager->updateUser($user, true);

        $manager->flush();
    }

    public function getOrder()
    {
        return 100;
    }

}
