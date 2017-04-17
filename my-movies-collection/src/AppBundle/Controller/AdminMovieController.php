<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cast;
use AppBundle\Entity\Movie;
use AppBundle\Entity\MovieCastRole;
use AppBundle\Entity\Poster;
use AppBundle\Entity\Role;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
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

            $roleDescription = $request->request->get('roleDescription');

            $movieCastRole = $this->createCastInMovieAsRoleEntry($cast, $movie, $role, $roleDescription);

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
     * Search results view with pagination
     *
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
     * Movie details from API view as form. You can add result from API as new movie to DB.
     *
     * @Route("/searchApiResult/{imdbId}")
     * @Template("AppBundle:Admin/Movie:searchApiResult.html.twig")
     * @param Request $request
     * @param $imdbId
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function apiTitleSearchResultAction(Request $request, $imdbId)
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

        if ($request->getMethod() === 'POST') {

            $title = $request->request->get('title');
            $yearOfProduction = $request->request->get('year');

            // check if movie with this title and year exist
            $movie = $this
                ->getDoctrine()
                ->getRepository('AppBundle:Movie')->findOneBy([
                    'title' => $title,
                    'yearOfProduction' => $yearOfProduction
                ]);

            if ($movie) {
                throw new Exception('Movie with title ' . $title . ' from year ' . $yearOfProduction . ' already added to collection!');
            }

            $em = $this
                ->getDoctrine()
                ->getManager();

            // create new movie from form
            $movie = new Movie();

            $em->persist($movie);

            $movie->setTitle($title);
            $movie->setYearOfProduction($yearOfProduction);

            // create new poster from form Url
            $poster = new Poster();

            $em->persist($poster);

            $posterUrl = $request->request->get('posterUrl');

            $poster->getFileFromUrl($posterUrl);

            $movie->addPoster($poster);
            $poster->setMovie($movie);

            // check if cast with this fullname exist

            /**
             * Get roles by names
             */
            $roleDirector = $this
                ->getDoctrine()
                ->getRepository('AppBundle:Role')->findOneBy([
                    'name' => 'director'
                ]);

            $roleWriter = $this
                ->getDoctrine()
                ->getRepository('AppBundle:Role')->findOneBy([
                    'name' => 'writer'
                ]);

            $roleActor = $this
                ->getDoctrine()
                ->getRepository('AppBundle:Role')->findOneBy([
                    'name' => 'actor'
                ]);

            /**
             * Arrays of cast full names
             */
            // We will delete parentheses with content eg. "Steven Spielberg (screenplay)"
            $patternToDelete = '/\(.*\)/';

            // directors
            $directorsNames = $request->request->get('director');
            $directorsNames = explode(',', $directorsNames);

            foreach ($directorsNames as $key => $value) {
                $directorsNames[$key] = trim(preg_replace($patternToDelete, '', $value));
            }

            $directorsNames = array_unique($directorsNames);

            // writers
            $writersNames = $request->request->get('writer');
            $writersNames = explode(',', $writersNames);

            foreach ($writersNames as $key => $value) {
                $writersNames[$key] = trim(preg_replace($patternToDelete, '', $value));
            }

            $writersNames = array_unique($writersNames);

            // actors
            $actorsNames = $request->request->get('actors');
            $actorsNames = explode(',', $actorsNames);

            foreach ($actorsNames as $key => $value) {
                $actorsNames[$key] = trim(preg_replace($patternToDelete, '', $value));
            }

            $actorsNames = array_unique($actorsNames);

            /**
             * Arrays of Cast objects
             */
            $directors = [];
            $writers = [];
            $actors = [];

            /**
             * Get existed Cast or create new one and put it into array
             */
            foreach ($directorsNames as $directorName) {

                $director = $this
                    ->getDoctrine()
                    ->getRepository('AppBundle:Cast')->findOneBy([
                        'fullName' => $directorName
                    ]);

                if (!$director) {
                    $director = new Cast();
                    $director->setFullName($directorName);

                    $em->persist($director);
                }

                $directors[] = $director;
            }

            /**
             * @ToDo: Dodaj sprawdzanie przy kolejnych rolach, czy nie został stworzony nowy Cast dla poprzednich ról.
             * Jeżeli tak, to weź tego Casta zamiast tworzyć nowego. Na razie, żeby uniknąć próby zapisywania dwa
             * razy tego samego Casta, robię flush() dla każdej roli po kolei.
             */

            /**
             * Connect entities - create new entries
             */
            $movieCastRoleEntries = [];

            foreach ($directors as $director) {

                $movieCastRoleEntry = $this->createCastInMovieAsRoleEntry($director, $movie, $roleDirector);

                $em->persist($movieCastRoleEntry);

                $movieCastRoleEntries[] = $movieCastRoleEntry;
            }

            $em->flush();

            /**
             * Get existed Cast or create new one and put it into array
             */
            foreach ($writersNames as $writerName) {

                $writer = $this
                    ->getDoctrine()
                    ->getRepository('AppBundle:Cast')->findOneBy([
                        'fullName' => $writerName
                    ]);

                if (!$writer) {
                    $writer = new Cast();
                    $writer->setFullName($writerName);

                    $em->persist($writer);
                }

                $writers[] = $writer;
            }

            /**
             * Connect entities - create new entries
             */
            foreach ($writers as $writer) {

                $movieCastRoleEntry = $this->createCastInMovieAsRoleEntry($writer, $movie, $roleWriter);

                $em->persist($movieCastRoleEntry);

                $movieCastRoleEntries[] = $movieCastRoleEntry;
            }

            $em->flush();

            /**
             * Get existed Cast or create new one and put it into array
             */
            foreach ($actorsNames as $actorName) {

                $actor = $this
                    ->getDoctrine()
                    ->getRepository('AppBundle:Cast')->findOneBy([
                        'fullName' => $actorName
                    ]);

                if (!$actor) {
                    $actor = new Cast();
                    $actor->setFullName($actorName);

                    $em->persist($actor);
                }

                $actors[] = $actor;
            }

            /**
             * Connect entities - create new entries
             */
            foreach ($actors as $actor) {

                $movieCastRoleEntry = $this->createCastInMovieAsRoleEntry($actor, $movie, $roleActor);

                $em->persist($movieCastRoleEntry);

                $movieCastRoleEntries[] = $movieCastRoleEntry;
            }

            $em->flush();

            return $this->redirectToRoute('app_adminmovie_show', ['movieId' => $movie->getId()]);
        }

        return [
            'movie' => $decodedJson
        ];
    }

    /**
     * Connect entities into one entry
     *
     * @param Cast $cast
     * @param Movie $movie
     * @param Role $role
     * @param string|null $roleDescription
     * @return MovieCastRole
     */
    public function createCastInMovieAsRoleEntry(Cast $cast, Movie $movie, Role $role, $roleDescription = null)
    {
        $movieCastRole = new MovieCastRole();

        $movieCastRole->setMovie($movie);
        $movieCastRole->setCast($cast);
        $movieCastRole->setRole($role);

        $movieCastRole->setRoleDescription($roleDescription);

        $movie->addMovieCastRole($movieCastRole);
        $cast->addMovieCastRole($movieCastRole);
        $role->addMovieCastRole($movieCastRole);

        return $movieCastRole;
    }
}
