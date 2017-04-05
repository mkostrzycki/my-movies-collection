<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cast;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/cast")
 */
class CastController extends Controller
{
    /**
     * @Route("/new")
     * @Template("AppBundle:Cast:new.html.twig")
     * @param Request $request
     * @return array
     */
    public function newAction(Request $request)
    {
        $cast = new Cast();

        $form = $this->createForm('AppBundle\Form\CastType', $cast);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($cast);
            $em->flush();

            return $this->redirectToRoute('app_cast_showall');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{castId}")
     * @Template("AppBundle:Cast:show.html.twig")
     * @param $castId
     * @return array
     */
    public function showAction($castId)
    {
        $cast = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Cast')->find($castId);

        if (!$cast) {
            throw $this->createNotFoundException('No cast found');
        }

        return [
            'cast' => $cast
        ];
    }

    /**
     * @Route("/")
     * @Template("AppBundle:Cast:showAll.html.twig")
     * @return array
     */
    public function showAll()
    {
        $entireCast = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Cast')->findAll();

        return [
            'entireCast' => $entireCast
        ];
    }
}
