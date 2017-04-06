<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use AppBundle\Entity\MovieCastRole;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/movie")
 */
class AdminMovieController extends Controller
{
    /**
     * @Route("/new")
     * @Template("AppBundle:Admin/Movie:new.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request)
    {
        $movie = new Movie();

        $form = $this->createForm('AppBundle\Form\MovieType', $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($movie);
            $em->flush();

            return $this->redirectToRoute('app_adminmovie_showall');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/show/{movieId}")
     * @Template("AppBundle:Admin/Movie:show.html.twig")
     * @param $movieId
     * @return array
     */
    public function showAction($movieId)
    {
        $movie = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Movie')->find($movieId);

        if (!$movie) {
            throw $this->createNotFoundException('No movie found');
        }

        return [
            'movie' => $movie
        ];
    }

    /**
     * @Route("/")
     * @Template("AppBundle:Admin/Movie:showAll.html.twig")
     * @return array
     */
    public function showAllAction()
    {
        $movies = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Movie')->findAll();

        return [
            'movies' => $movies
        ];
    }

    /**
     * @Route("/{movieId}/addCastAsRole")
     * @Template("AppBundle:Admin/Movie:addCastAsRole.html.twig")
     * @param Request $request
     * @param $movieId
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addCastAsRoleAction(Request $request, $movieId)
    {
        $movie = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Movie')->find($movieId);

        $entireCast = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Cast')->findAll();

        $roles = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Role')->findAll();

        if ($request->getMethod() === 'POST') {

            $cast = $this
                ->getDoctrine()
                ->getRepository('AppBundle:Cast')->find($request->request->get('castId'));

            $role = $this
                ->getDoctrine()
                ->getRepository('AppBundle:Role')->find($request->request->get('roleId'));

            $movieCastRole = new MovieCastRole();

            $movieCastRole->setMovie($movie);
            $movieCastRole->setCast($cast);
            $movieCastRole->setRole($role);

            $movie->addMovieCastRole($movieCastRole);
            $cast->addMovieCastRole($movieCastRole);
            $role->addMovieCastRole($movieCastRole);

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($movieCastRole);
            $em->flush();

            return $this->redirectToRoute('app_adminmovie_show', ['movieId' => $movieId]);
        }

        return [
            'movie' => $movie,
            'entireCast' => $entireCast,
            'roles' => $roles
        ];
    }
}
