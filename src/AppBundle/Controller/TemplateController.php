<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Template;
use AppBundle\Form\TemplateType;

/**
 * Template controller.
 *
 * @Route("/template")
 */
class TemplateController extends Controller
{

    /**
     * Lists all Template entities.
     *
     * @Route("/", name="templates")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Template')->findAll();

        return $this->render(':template:index.html.twig', [
            'entities' => $entities,
        ]);
    }
    /**
     * Creates a new Template entity.
     *
     * @Route("/", name="template_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $entity = new Template();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('template_show', array('id' => $entity->getId())));
        }

        return $this->render(':template:new.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * Creates a form to create a Template entity.
     *
     * @param Template $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Template $entity)
    {
        $form = $this->createForm(new TemplateType(), $entity, array(
            'action' => $this->generateUrl('template_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Template entity.
     *
     * @Route("/new", name="template_new")
     * @Method("GET")
     */
    public function newAction()
    {
        $entity = new Template();
        $form   = $this->createCreateForm($entity);

        return $this->render(':template:new.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Template entity.
     *
     * @Route("/{id}", name="template_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Template')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Template entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render(':template:show.html.twig', [
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Template entity.
     *
     * @Route("/{id}/edit", name="template_edit")
     * @Method("GET")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Template')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Template entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render(':template:edit.html.twig', [
            'entity' => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
    * Creates a form to edit a Template entity.
    *
    * @param Template $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Template $entity)
    {
        $form = $this->createForm(new TemplateType(), $entity, array(
            'action' => $this->generateUrl('template_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Template entity.
     *
     * @Route("/{id}", name="template_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Template')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Template entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('templates'));
        }

        return $this->render(':template:edit.html.twig', [
            'entity' => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }
    /**
     * Deletes a Template entity.
     *
     * @Route("/{id}", name="template_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Template')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Template entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('template'));
    }

    /**
     * Creates a form to delete a Template entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('template_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
