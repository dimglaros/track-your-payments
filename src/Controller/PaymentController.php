<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Exception\NonValidPaymentTypeException;
use App\Exception\NonValidUserException;
use App\Repository\PaymentTypeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @var PaymentTypeRepository
     */
    private $paymentTypeRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        PaymentTypeRepository $paymentTypeRepository
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->paymentTypeRepository = $paymentTypeRepository;
    }

    public function create(Request $request): JsonResponse
    {
        $payment = new Payment();
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
        $payment->setUser($user);

        try {
            $payment->setType($this->paymentTypeRepository->findByName(
                $request->request->get('paymentType', '')
            ));
        } catch (NonValidPaymentTypeException $e) {
            return JsonResponse::create(
                ['errorMessage' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
        $payment->setDescription(
            $request->request->get('description')
        );
        $payment->setAmount($request->request->getInt('amount'));
        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        return JsonResponse::create(
            ['Response' => 'Success'],
            Response::HTTP_OK
        );
    }
}
