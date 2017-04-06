<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use AppBundle\Entity\Poster;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/poster")
 */
class AdminPosterController extends Controller
{
    /**
     * @Route("/{movieId}/new")
     * @Template("AppBundle:Admin/Poster:new.html.twig")
     * @param Request $request
     * @param $movieId
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request, $movieId)
    {
        $poster = new Poster();

        /** @var Movie */
        $movie = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Movie')->find($movieId);

        if (!$movie) {
            throw $this->createNotFoundException('Movie not found');
        }

        $form = $this->createForm('AppBundle\Form\PosterType', $poster);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($poster);

            $poster->setMovie($movie);
            $movie->addPoster($poster);

            $em->flush();

            return $this->redirectToRoute('app_adminmovie_show', ['movieId' => $movieId]);
        }

        return [
            'movie' => $movie,
            'form' => $form->createView()
        ];
    }
}
