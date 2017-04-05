<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/role")
 */
class RoleController extends Controller
{
    /**
     * @Route("/new")
     * @Template("AppBundle:Role:new.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request)
    {
        $role = new Role();

        $form = $this->createForm('AppBundle\Form\RoleType', $role);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($role);
            $em->flush();

            return $this->redirectToRoute('app_role_showall');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/")
     * @Template("AppBundle:Role:showAll.html.twig")
     * @return array
     */
    public function showAllAction()
    {
        $roles = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Role')->findAll();

        return [
            'roles' => $roles
        ];
    }
}
