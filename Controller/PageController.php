<?php

namespace Bafford\SimpleCmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Bafford\SimpleCmsBundle\Entity\Page;
use Bafford\SimpleCmsBundle\Form\Type\PageType;

/**
 * Page controller.
 *
 * @Route("/admin/pages")
 */
class PageController extends Controller
{
    public function createForm($formType, $entity = null, array $options = [])
    {
        if($formType instanceof PageType)
        {
            return parent::createForm($formType, $entity, ['requiredPage' => $entity->getRequired()]);
        }
        else
            return parent::createForm($formType, $entity);
    }
    
    /**
     * Lists all Page entities.
     *
     * @Route("/", name="bafford_simplecms_admin_pages")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BaffordSimpleCmsBundle:Page')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Page entity.
     *
     * @Route("/{id}/show", name="bafford_simplecms_admin_pages_show")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BaffordSimpleCmsBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Page entity.
     *
     * @Route("/new", name="bafford_simplecms_admin_pages_new")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Page();
        $form   = $this->createForm(new PageType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Page entity.
     *
     * @Route("/create", name="bafford_simplecms_admin_pages_create")
     * @Method("POST")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("BaffordSimpleCmsBundle:Page:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Page();
        $form = $this->createForm(new PageType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bafford_simplecms_admin_pages_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Page entity.
     *
     * @Route("/{id}/edit", name="bafford_simplecms_admin_pages_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BaffordSimpleCmsBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $editForm = $this->createForm(new PageType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Page entity.
     *
     * @Route("/{id}/update", name="bafford_simplecms_admin_pages_update")
     * @Method("POST")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("BaffordSimpleCmsBundle:Page:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BaffordSimpleCmsBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PageType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bafford_simplecms_admin_pages_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Page entity.
     *
     * @Route("/{id}/delete", name="bafford_simplecms_admin_pages_delete")
     * @Secure(roles="ROLE_ADMIN")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BaffordSimpleCmsBundle:Page')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Page entity.');
            }
            
            if ($entity->getRequired())
                throw new \Exception('This page is required and can not be deleted.');

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bafford_simplecms_admin_pages'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
