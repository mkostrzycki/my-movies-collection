<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/movie")
 */
class MovieController extends Controller
{
    /**
     * @Route("/grid")
     * @Template("AppBundle:Front/Movie:showGrid.html.twig")
     * @return array
     */
    public function showGridAction()
    {
        $movies = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Movie')->findAll();

        return [
            'movies' => $movies
        ];
    }

    /**
     * @Route("/pagination")
     * @Template("AppBundle:Front/Movie:paginationTest.html.twig")
     * @param Request $request
     * @return array
     */
    public function paginationTestAction(Request $request)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();

        $dql = "SELECT m FROM AppBundle:Movie m";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );

        return [
            'pagination' => $pagination
        ];
    }

    /**
     * @Route("/{movieId}/show")
     * @Template("AppBundle:Front/Movie:showMovieDetails.html.twig")
     * @param $movieId
     * @return array
     */
    public function showMovieDetails($movieId)
    {
        $movie = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Movie')->find($movieId);

        if (!$movie) {
            throw $this->createNotFoundException('Movie not found');
        }

        return [
            'movie' => $movie
        ];
    }
}
