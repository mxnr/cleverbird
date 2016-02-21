<?php

namespace CleverBirdBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class ExceptionController extends Controller
{
    /**
     * @return Response
     */
    public function showExceptionAction(
        $exception
    ) {
        if ($exception->getStatusCode() == 404) {
            $title = 'Page not found.';
        } elseif ($exception->getStatusCode() == 403) {
            $title = 'You don\'t have access to this page.';
        } else {
            $title = 'Something is broken, sorry:(.';
        }

        return $this->render(
            '@CleverBird/Exception/error.html.twig', ['title' => $title]
        );
    }
}
