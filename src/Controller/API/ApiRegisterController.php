<?php

namespace Zantolov\AppBundle\Controller\API;

use Zantolov\AppBundle\Entity\ApiToken;
use Zantolov\AppBundle\Entity\User;
use Doctrine\Common\Util\Debug;
use FOS\UserBundle\Doctrine\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

/**
 *
 * Login Controller
 *
 * @Route("/api")
 */
class ApiRegisterController extends ApiController
{
    /**
     *
     * @Route("/register", name="api.register")
     * @Method("POST")
     */
    public function loginAction(Request $request)
    {
        $data = $this->getDataFromRequest($request);

        if (
            (!isset($data['username']) || empty($data['username'])) &&
            (!isset($data['email']) || empty($data['email']))
        ) {
            return $this->createResponse(array(
                self::KEY_STATUS  => self::STATUS_ERROR,
                self::KEY_MESSAGE => 'username or email not provided'));
        }

        if (!isset($data['password']) || empty($data['password'])) {
            return $this->createResponse(array(
                self::KEY_STATUS  => self::STATUS_ERROR,
                self::KEY_MESSAGE => 'password not provided'));
        }

        $username = @$data['username'];
        $email = @$data['email'];
        $password = $data['password'];

        /** @var UserManager $user_manager */
        $user_manager = $this->get('fos_user.user_manager');

        $userUsername = $user_manager->findUserBy(array('username' => $username));
        $userEmail = $user_manager->findUserBy(array('email' => $email));

        if (!empty($userEmail) || !empty($userUsername)) {
            return $this->createResponse(array(
                self::KEY_STATUS  => self::STATUS_ERROR,
                self::KEY_MESSAGE => 'User exists'));
        }

        /** @var User $newUser */
        $newUser = $user_manager->createUser();

        $newUser->setEmail($email);
        $newUser->setPlainPassword($password);
        $newUser->setUsername(($username ?: $email));
        $newUser->setRoles(array('ROLE_USER'));
        $newUser->setEnabled(true);

        $user_manager->updateUser($newUser);

        return $this->createResponse(array(
                self::KEY_STATUS => self::STATUS_OK,
                self::KEY_DATA   => array(
                    'username' => $newUser->getUsername(),
                    'email'    => $newUser->getEmail(),
                ))
        );

    }

}