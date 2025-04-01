<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\CallRequest;
use App\Entity\ClientRequest;
use App\Entity\OrderRequest;
use App\Entity\RideRequest;
use App\Entity\ServiceRequest;
use App\Form\CallRequestFormType;
use App\Form\OrderRequestFormType;
use App\Form\RideRequestFormType;
use App\Form\ServiceRequestFormType;
use App\Service\EmailNotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientRequestController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EmailNotificationService
     */
    private $notificationService;

    /**
     * ClientRequestController constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param EmailNotificationService $notificationService
     */
    public function __construct(EntityManagerInterface $entityManager, EmailNotificationService $notificationService)
    {
        $this->entityManager = $entityManager;
        $this->notificationService = $notificationService;
    }

    /**
     * @Route("/api/request/order", name="request_order", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createOrderRequest(Request $request): JsonResponse
    {
        return $this->createRequest($request, new OrderRequest(), OrderRequestFormType::class);
    }

    /**
     * @Route("/api/request/ride", name="request_ride", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createRideRequest(Request $request): JsonResponse
    {
        return $this->createRequest($request, new RideRequest(), RideRequestFormType::class);
    }

    /**
     * @Route("/api/request/service", name="request_service", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createServiceRequest(Request $request): JsonResponse
    {
        return $this->createRequest($request, new ServiceRequest(), ServiceRequestFormType::class);
    }

    /**
     * @Route("/api/request/call", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createCallRequest(Request $request): JsonResponse
    {
        return $this->createRequest($request, new CallRequest(), CallRequestFormType::class);
    }

    /**
     * @param Request       $request
     * @param ClientRequest $clientRequest
     * @param string        $type
     *
     * @return JsonResponse
     */
    private function createRequest(Request $request, ClientRequest $clientRequest, string $type): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE === json_last_error()) {
            $form = $this->createForm($type, $clientRequest);
            $form->submit($data);

            if ($form->isValid()) {
//                $clientRequest->setReferer($request->headers->get('referer'));

//                $this->entityManager->persist($clientRequest);
//                $this->entityManager->flush();

//                $this->notificationService->sendClientRequestNotificationEmail($clientRequest);

                return $this->respondOk();
            }

            return $this->respondValidationErrors($form);
        }

        return $this->respondInvalidJson();
    }

    /**
     * @return JsonResponse
     */
    private function respondOk(): JsonResponse
    {
        return new JsonResponse([
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }

    /**
     * @param FormInterface $form
     *
     * @return JsonResponse
     */
    private function respondValidationErrors(FormInterface $form): JsonResponse
    {
        return new JsonResponse([
            'status' => Response::HTTP_BAD_REQUEST,
            'type'   => 'validation_errors',
            'errors' => $this->getFormErrors($form),
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return JsonResponse
     */
    private function respondInvalidJson(): JsonResponse
    {
        return new JsonResponse([
            'status' => Response::HTTP_BAD_REQUEST,
            'type'   => 'invalid_json',
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param FormInterface $form
     *
     * @return array
     */
    private function getFormErrors(FormInterface $form)
    {
        $results = [];

        foreach ($form->getErrors() as $error) {
            $results['form'][] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if ($child instanceof FormInterface) {
                $errors = $this->getFormErrors($child);
                if ($errors) {
                    $results[$child->getName()] = $errors['form'];
                }
            }
        }

        return $results;
    }
}
