<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RedirectionController extends AbstractController
{
    public function index(Request $request): Response
    {
        $locale = $request->getPreferredLanguage(['fr', 'en']) ?? 'fr';

        return $this->redirectToRoute('app_debate_index', [
            '_locale' => $locale,
        ]);
    }
}
