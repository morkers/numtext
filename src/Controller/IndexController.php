<?php

namespace App\Controller;

use App\Service\LvNumberToWords;
use NumberToWords\NumberToWords;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends AbstractController
{
    #[Route('/{number}/{language}')]
    public function index(int $number, string $language, NumberToWords $numbersToWords): Response
    {
        if ($language == 'lv') {
            $result = LvNumberToWords::toWords($number);
        } else {
            $result = $numbersToWords
                ->getNumberTransformer($language)
                ->toWords($number);
        }

        $response = $this->json($result);

        return $response->setEncodingOptions($response->getEncodingOptions() | JSON_UNESCAPED_UNICODE);
    }
}
