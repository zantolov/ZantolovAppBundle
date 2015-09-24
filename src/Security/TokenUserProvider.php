<?php

namespace Zantolov\AppBundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Zantolov\AppBundle\Entity\ApiToken;
use Zantolov\AppBundle\Entity\User;

class TokenUserProvider implements UserProviderInterface, ContainerAwareInterface
{

    /** @var  ContainerInterface */
    protected $container;

    public function  __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    public function loadUserByUsername($username)
    {
    }


    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param $token
     * @return User|null
     */
    public function getUserByToken($token)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getEntityManager();

        /** @var ApiToken $token */
        $token = $em->getRepository(ApiToken::class)->findOneBy(array('token' => $token));

        if (empty($token)) {
            return null;
        } else {
            return $token->getUser();
        }
    }


    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}
