<?php

namespace App\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler extends AbstractController implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException): Response
    {
        $referer = $request->headers->get('referer');
        $this->addFlash('danger', 'You are not authorized to access this site!');
        if (!$referer && $this->getUser()) {
            return $this->redirectToRoute('dashboard');
        } elseif (!$referer) {
            return $this->redirectToRoute('landing_page');
        }
        return $this->redirect($referer);
    }
}