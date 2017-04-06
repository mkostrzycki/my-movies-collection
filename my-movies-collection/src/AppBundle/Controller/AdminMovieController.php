<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use AppBundle\Entity\MovieCastRole;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
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

    /**
     * @Route("/searchApi")
     * @Template("AppBundle:Admin/Movie:searchApi.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apiTitleSearchAction(Request $request)
    {
        $defaultData = ['search' => ''];

        $form = $this->createFormBuilder($defaultData)
            ->add('search', SearchType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $searchPhrase = $form->get('search')->getData();
            $encodedSearchPhrase = urlencode($searchPhrase);

            return $this->redirectToRoute('app_adminmovie_apititlesearchresults', [
                'searchPhrase' => $encodedSearchPhrase,
                'pageNumber' => 1
            ]);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/searchApiResults/{searchPhrase}/{pageNumber}", defaults={"pageNumber = 1"})
     * @Template("AppBundle:Admin/Movie:searchApiResults.html.twig")
     * @param $searchPhrase
     * @param $pageNumber
     * @return array
     */
    public function apiTitleSearchResultsAction($searchPhrase, $pageNumber)
    {
        /** @var \GuzzleHttp\Client $client */
        $client = $this->get('guzzle.client.api_omdb');

        $decodedSearchPhrase = urldecode($searchPhrase);

        $response = $client->get('?', [
            'query' => [
                's' => $decodedSearchPhrase,
                'type' => 'movie',
                'page' => $pageNumber,
                'r' => 'json'
            ]
        ]);

        $json = $response->getBody();

        $decodedJson = \GuzzleHttp\json_decode($json, true);

        if (array_key_exists('Error', $decodedJson)) {
            $pages = 0;
            $decodedJson = '';
        } else {
            $pages = ceil($decodedJson['totalResults'] / 10);
        }

        return [
            'decodedJson' => $decodedJson,
            'encodedSearchPhrase' => $searchPhrase,
            'decodedSearchPhrase' => $decodedSearchPhrase,
            'pages' => $pages
        ];
    }

    /**
     * @Route("/searchApiResult/{imdbId}")
     * @Template("AppBundle:Admin/Movie:searchApiResult.html.twig")
     * @param $imdbId
     * @return array
     */
    public function apiTitleSearchResultAction($imdbId)
    {
        /** @var \GuzzleHttp\Client $client */
        $client = $this->get('guzzle.client.api_omdb');

        $response = $client->get('?', [
            'query' => [
                'i' => $imdbId,
                'type' => 'movie',
                'plot' => 'short',
                'r' => 'json'
            ]
        ]);

        $json = $response->getBody();

        $decodedJson = \GuzzleHttp\json_decode($json, true);

        return [
            'movie' => $decodedJson
        ];
    }
}
