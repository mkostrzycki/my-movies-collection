<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AgeCategory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/ageCategory")
 */
class AdminAgeCategoryController extends Controller
{
    /**
     * @Route("/new")
     * @Template("AppBundle:Admin/AgeCategory:new.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request)
    {
        $ageCategory = new AgeCategory();

        $form = $this->createForm('AppBundle\Form\AgeCategoryType', $ageCategory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this
                ->getDoctrine()
                ->getManager();

            $em->persist($ageCategory);
            $em->flush();

            return $this->redirectToRoute('app_adminagecategory_showall');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/")
     * @Template("AppBundle:Admin/AgeCategory:showAll.html.twig")
     * @return array
     */
    public function showAllAction()
    {
        $ageCategories = $this
            ->getDoctrine()
            ->getRepository('AppBundle:AgeCategory')->findAll();

        return [
            'ageCategories' => $ageCategories
        ];
    }
}
