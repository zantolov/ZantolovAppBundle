<?php

namespace Zantolov\AppBundle\EventListener;

use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Zantolov\AppBundle\Controller\API\ApiController;


class ExceptionListener
{

    public function onKernelException(GetResponseForExceptionEvent $event)
    {

        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        if ($event->getRequest()->getContentType() === 'json') {

            $exception = $event->getException();
            $response = new JsonResponse(
                array(
                    ApiController::KEY_STATUS  => ApiController::STATUS_ERROR,
                    ApiController::KEY_MESSAGE => 'Error occured',
                    ApiController::KEY_DATA    => array(
                        'message' => sprintf(
                            'Error: %s; Code: %s',
                            $exception->getMessage(),
                            $exception->getCode()
                        )
                    )
                )
            );
            // You get the exception object from the received event
            $message = sprintf(
                'My Error says: %s with code: %s',
                $exception->getMessage(),
                $exception->getCode()
            );

            // HttpExceptionInterface is a special type of exception that
            // holds status code and header details
            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            // Send the modified response object to the event
            $event->setResponse($response);
        }

    }

}