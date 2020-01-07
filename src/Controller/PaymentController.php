<?php

namespace App\Controller;

use App\Exception\NonValidPaymentTypeException;
use App\Exception\NonValidUserException;
use App\Message\PaymentCreation;
use App\Repository\PaymentRepository;
use App\Repository\PaymentTypeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class PaymentController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var PaymentTypeRepository
     */
    private $paymentTypeRepository;
    /**
     * @var MessageBusInterface
     */
    private $bus;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        PaymentRepository $paymentRepository,
        PaymentTypeRepository $paymentTypeRepository,
        MessageBusInterface $bus
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->paymentRepository = $paymentRepository;
        $this->paymentTypeRepository = $paymentTypeRepository;
        $this->bus = $bus;
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $user = $this->userRepository->findByApiToken(
                $request->headers->get('x-api-key', '')
            );
        } catch (NonValidUserException $e) {
            return JsonResponse::create(
                [],
                Response::HTTP_UNAUTHORIZED
            );
        }

        try {
            $paymentType = $this->paymentTypeRepository->findByName(
                $request->request->get('paymentType', '')
            );
        } catch (NonValidPaymentTypeException $e) {
            return JsonResponse::create(
                ['errorMessage' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        if(is_null($request->request->get('description'))) {
            return JsonResponse::create(
                ['errorMessage' => 'Description attribute is missing'],
                Response::HTTP_BAD_REQUEST
            );
        }

        if(is_null($request->request->get('amount'))) {
            return JsonResponse::create(
                ['errorMessage' => 'Amount attribute is missing'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->bus->dispatch(new PaymentCreation(
            $user->getId(),
            $paymentType->getId(),
            $request->request->get('description'),
            $request->request->get('amount')
        ));

        return JsonResponse::create(
            [],
            Response::HTTP_ACCEPTED
        );
    }

    public function getOne(string $id)
    {
        $payment = $this->paymentRepository->find($id);
        if(!$payment) {
            return JsonResponse::create(
                [],
                Response::HTTP_NOT_FOUND
            );
        }

        return JsonResponse::create($payment, Response::HTTP_OK);
    }
}
