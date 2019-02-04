<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Marque;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Marque controller.
 *
 * @Route("marque")
 */
class MarqueController extends Controller
{
    /**
     * Lists all marque entities.
     *
     * @Route("/", name="marque_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $marques = $em->getRepository('AppBundle:Marque')->findAll();
        $marque = new Marque();
        $form = $this->createForm('AppBundle\Form\MarqueType', $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Verification d'existence du libelle dans la BD
            $existence = $em->getRepository('AppBundle:Marque')->findOneBy(['libelle'=> $marque->getLibelle()]);
            if ($existence){
                $this->addFlash('notice',"Attention cette marque existe déjà. Veuillez entrer une autre marque.");
                return $this->redirectToRoute('marque_index');

            }
            $em->persist($marque);
            $em->flush();

            return $this->redirectToRoute('marque_index');
        }

        return $this->render('marque/index.html.twig', array(
            'marques' => $marques,
            'current_menu' => 'monture',
            'marque' => $marque,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new marque entity.
     *
     * @Route("/new", name="marque_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $marque = new Marque();
        $form = $this->createForm('AppBundle\Form\MarqueType', $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($marque);
            $em->flush();

            return $this->redirectToRoute('marque_show', array('id' => $marque->getId()));
        }

        return $this->render('marque/new.html.twig', array(
            'marque' => $marque,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a marque entity.
     *
     * @Route("/{id}", name="marque_show")
     * @Method("GET")
     */
    public function showAction(Marque $marque)
    {
        $deleteForm = $this->createDeleteForm($marque);

        return $this->render('marque/show.html.twig', array(
            'marque' => $marque,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing marque entity.
     *
     * @Route("/{slug}/edit", name="marque_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Marque $marque)
    {
        $deleteForm = $this->createDeleteForm($marque);
        $editForm = $this->createForm('AppBundle\Form\MarqueType', $marque);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('marque_index');
        }
        $em = $this->getDoctrine()->getManager();

        $marques = $em->getRepository('AppBundle:Marque')->findAll();

        return $this->render('marque/edit.html.twig', array(
            'marque' => $marque,
            'marques' => $marques,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'current_menu' => 'monture'
        ));
    }

    /**
     * Deletes a marque entity.
     *
     * @Route("/{id}", name="marque_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Marque $marque)
    {
        $form = $this->createDeleteForm($marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($marque);
            $em->flush();
        }

        return $this->redirectToRoute('marque_index');
    }

    /**
     * Creates a form to delete a marque entity.
     *
     * @param Marque $marque The marque entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Marque $marque)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('marque_delete', array('id' => $marque->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
