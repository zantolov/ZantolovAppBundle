<?php

namespace Zantolov\AppBundle\Controller\API;

use Zantolov\AppBundle\Entity\User;
use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\RecursiveValidator;

abstract class ApiController extends Controller
{

    const STATUS_ERROR = 'error';
    const STATUS_OK = 'ok';

    const KEY_MESSAGE = 'message';
    const KEY_DATA = 'data';
    const KEY_STATUS = 'status';

    protected static $keys = array(
        self::KEY_STATUS,
        self::KEY_MESSAGE,
        self::KEY_DATA,
    );


    /**
     * @param array $data
     * @return array
     */
    protected function filterData(array $data)
    {
        $filtered = array();

        foreach (self::$keys as $key) {
            if (isset($data[$key])) {
                $filtered[$key] = $data[$key];
            }
        }

        return $filtered;
    }


    /**
     * @param $data
     * @return int
     * @throws \Exception
     */
    protected function getStatusCodeForResponse($data)
    {
        if (!isset($data[self::KEY_STATUS])) {
            throw new \Exception('Data doesnt have STATUS key');
        }

        switch ($data[self::KEY_STATUS]) {
            case self::STATUS_ERROR:
                return 400;
                break;
            case self::STATUS_OK:
                return 200;
                break;
        }

        throw new \Exception('Status key not recognized');

    }


    /**
     * @param $data
     * @return array
     */
    protected function createResponse($data, $headers = array())
    {
        $data = $this->filterData($data);
        $responseCode = $this->getStatusCodeForResponse($data);

        return new JsonResponse($data, $responseCode, $headers);
    }


    /**
     * @return mixed
     */
    protected function getCurrentUser()
    {
        return $this->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * @param $user
     */
    protected function checkOwner($user)
    {
        if ($user instanceof User) {
            $user = $user->getId();
        }

        if ($user != $this->getCurrentUser()->getId()) {
            throw new AccessDeniedException('You dont have right to acces this data');
        }

    }


    /**
     * @param $object
     * @return array|bool
     */
    public function validate($object)
    {
        /** @var RecursiveValidator $validator */
        $validator = $this->get('validator');
        $errors = $validator->validate($object);

        if ($errors->count() > 0) {

            $errorData = array();
            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $errorData[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->createResponse(array(
                self::KEY_STATUS  => self::STATUS_ERROR,
                self::KEY_MESSAGE => 'error validating object',
                self::KEY_DATA    => $errorData,
            ));
        }

        return true;
    }


    /***
     * @param Request $request
     * @return mixed|null
     */
    public function getDataFromRequest(Request $request)
    {
        $data = $request->getContent();

        if (is_array($data)) {
            return $data;
        }
        try {
            $data = json_decode($data, true);
            return $data;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param string $msg
     * @param array $data
     * @param array $headers
     * @return array
     */
    protected function createErrorResponse($msg = 'Error', $data = array(), $headers = array())
    {
        return $this->createResponse(array(
            self::KEY_STATUS  => self::STATUS_ERROR,
            self::KEY_DATA    => $data,
            self::KEY_MESSAGE => $msg,
        ), $headers);
    }

}