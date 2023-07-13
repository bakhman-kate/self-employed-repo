<?php

namespace App\Controller;

use App\Entity\Inn;
use App\Form\Type\InnType;
use App\Service\Inn\InnInterface;
use App\Service\TaxPayer\TaxPayerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class SelfEmployedController extends AbstractController
{
    private const SUCCESSFUL_CODE = 200;
    private const INCORRECT_INN_CODE = 500;

    private InnInterface $innService;

    private TaxPayerInterface $taxPayerService;

    private int $code;

    private string $message;

    public function __construct(
        InnInterface      $innService,
        TaxPayerInterface $taxPayerService,
        int               $code = self::SUCCESSFUL_CODE,
        string            $message = ''
    ) {

        $this->innService = $innService;
        $this->taxPayerService = $taxPayerService;
        $this->code = $code;
        $this->message = $message;
    }

    #[Route('/')]
    public function status(Request $request): Response
    {
        $form = $this->createForm(InnType::class, new Inn());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Inn $innEntity */
            $innEntity = $form->getData();
            $inn = $innEntity->getValue();

            if (!$this->innService->isIndividualInn($inn)) {
                $this->code = self::INCORRECT_INN_CODE;
                $this->message = 'Неверный ИНН';
            } else {
                $status = $this->taxPayerService->getStatus($inn, date('Y-m-d'));
                if (array_key_exists('code', $status)) {
                    $this->code = (int) $status['code'];
                }
                if (array_key_exists('message', $status)) {
                    $this->message = $status['message'];
                }
            }
        }

        return $this->render('self-employed/form.html.twig', [
            'form' => $form,
            'code' => $this->code,
            'message' => $this->message,
        ]);
    }
}
