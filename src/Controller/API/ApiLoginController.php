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
class ApiLoginController extends ApiController
{
    /**
     *
     * @Route("/login", name="api.login")
     * @Method("POST")
     */
    public function loginAction(Request $request)
    {
        $data = $this->getDataFromRequest($request);

        if (
            (!isset($data['username']) || empty($data['username'])) &&
            (!isset($data['email']) || empty($data['email']))
        ) {
            return $this->createResponse(array(self::KEY_STATUS => self::STATUS_ERROR, self::KEY_MESSAGE => 'username or email not provided'));
        }

        if (!isset($data['password']) || empty($data['password'])) {
            return $this->createResponse(array(self::KEY_STATUS => self::STATUS_ERROR, self::KEY_MESSAGE => 'username not provided'));
        }

        $username = @$data['username'];
        $email = @$data['email'];
        $password = $data['password'];


        /** @var UserManager $user_manager */
        $user_manager = $this->get('fos_user.user_manager');

        /** @var EncoderFactory $factory */
        $factory = $this->get('security.encoder_factory');

        $findBy = array();
        if (!empty($email)) {
            $findBy['email'] = $email;
        }
        if (!empty($username)) {
            $findBy['username'] = $username;
        }

        /** @var User $user */
        $user = $user_manager->findUserBy($findBy);
        if (empty($user)) {
            return $this->createResponse(array(self::KEY_STATUS => self::STATUS_ERROR, self::KEY_MESSAGE => 'User not found'));
        }

        $encoder = $factory->getEncoder($user);

        $valid = ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) ? true : false;

        if (!$valid) {
            return $this->createResponse(array(self::KEY_STATUS => self::STATUS_ERROR, self::KEY_MESSAGE => 'User data invalid'));
        }

        $token = new ApiToken();
        $token->setUser($user);
        $token->setToken(sha1(uniqid()));
        $token->setUserAgent($request->headers->get('User-Agent'));

        $user->setGcmRegistrationId(@$data['registrationId']);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->persist($token);
        $this->getDoctrine()->getManager()->flush();

        return $this->createResponse(array(
                self::KEY_STATUS => self::STATUS_OK,
                self::KEY_DATA   => array(
                    'username'       => $user->getUsername(),
                    'email'          => $user->getEmail(),
                    'token'          => $token->getToken(),
                    'registrationId' => $user->getGcmRegistrationId(),
                ))
        );

    }


    /**
     *
     * @Route("/logout", name="api.logout")
     * @Method("POST")
     */
    public function logoutAction(Request $r)
    {
        /** @var User $user */
        $user = $this->getCurrentUser();

        if (empty($user) || !($user instanceof User)) {
            return $this->createResponse(array(self::KEY_STATUS => self::STATUS_ERROR, self::KEY_MESSAGE => 'User not logged in'));
        }

        $token = $user->getActiveToken();
        $this->getDoctrine()->getManager()->remove($token);
        $this->getDoctrine()->getManager()->flush();

        return $this->createResponse(array(self::KEY_STATUS => self::STATUS_OK, self::KEY_MESSAGE => 'User logged out', self::KEY_DATA => array('username' => $user->getUsername())));
    }


    /**
     *
     * @Route("/login/check", name="api.login.check")
     * @Method("POST")
     */
    public function checkAction(Request $r)
    {
        /** @var User $user */
        $user = $this->getCurrentUser();

        if (empty($user) || !($user instanceof User)) {
            return $this->createResponse(array(self::KEY_STATUS => self::STATUS_ERROR, self::KEY_MESSAGE => 'User not logged in'));
        }

        return $this->createResponse(array(self::KEY_STATUS => self::STATUS_OK, self::KEY_MESSAGE => 'User logged in', self::KEY_DATA => array('username' => $user->getUsername())));
    }

}