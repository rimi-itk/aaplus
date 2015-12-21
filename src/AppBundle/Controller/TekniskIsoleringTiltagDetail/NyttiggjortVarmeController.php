<?php

namespace AppBundle\Controller\TekniskIsoleringTiltagDetail;

use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\TekniskIsoleringTiltagDetail\NyttiggjortVarme;
use AppBundle\Form\TekniskIsoleringTiltagDetail\NyttiggjortVarmeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * TekniskIsoleringTiltagDetail\NyttiggjortVarme controller.
 *
 * @Route("/nyttiggjortvarme")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class NyttiggjortVarmeController extends BaseController {

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('nyttiggjortvarme.labels.plural', $this->generateUrl('nyttiggjortvarme'));
  }

  /**
   * Lists all TekniskIsoleringTiltagDetail\NyttiggjortVarme entities.
   *
   * @Route("/", name="nyttiggjortvarme")
   * @Method("GET")
   * @Template()
   */
  public function indexAction() {
    $em = $this->getDoctrine()->getManager();

    $entities = $em->getRepository('AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme')->findAll();

    return array(
      'entities' => $entities,
    );
  }

  /**
   * Creates a new TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.
   *
   * @Route("/", name="nyttiggjortvarme_create")
   * @Method("POST")
   * @Template("AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme:new.html.twig")
   */
  public function createAction(Request $request) {
    $entity = new NyttiggjortVarme();
    $form = $this->createCreateForm($entity);
    $form->handleRequest($request);

    if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('nyttiggjortvarme_show', array('id' => $entity->getId())));
    }

    return array(
      'entity' => $entity,
      'form' => $form->createView(),
    );
  }

  /**
   * Creates a form to create a TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.
   *
   * @param NyttiggjortVarme $entity The entity
   *
   * @return \Symfony\Component\Form\Form The form
   */
  private function createCreateForm(NyttiggjortVarme $entity) {
    $form = $this->createForm(new NyttiggjortVarmeType(), $entity, array(
      'action' => $this->generateUrl('nyttiggjortvarme_create'),
      'method' => 'POST',
    ));

    $form->add('submit', 'submit', array('label' => 'Create'));

    return $form;
  }

  /**
   * Displays a form to create a new TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.
   *
   * @Route("/new", name="nyttiggjortvarme_new")
   * @Method("GET")
   * @Template()
   */
  public function newAction() {
    $this->breadcrumbs->addItem('common.create', $this->generateUrl('nyttiggjortvarme_create'));

    $entity = new NyttiggjortVarme();
    $form = $this->createCreateForm($entity);

    return array(
      'entity' => $entity,
      'form' => $form->createView(),
    );
  }

  /**
   * Displays a form to edit an existing TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.
   *
   * @Route("/{id}/edit", name="nyttiggjortvarme_edit")
   * @Method("GET")
   * @Template()
   */
  public function editAction($id) {
    $this->breadcrumbs->addItem('common.edit', $this->generateUrl('nyttiggjortvarme_edit', array('id' => $id)));

    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme')->find($id);

    if (!$entity) {
      throw $this->createNotFoundException('Unable to find TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.');
    }

    $editForm = $this->createEditForm($entity);

    return array(
      'entity' => $entity,
      'edit_form' => $editForm->createView(),
    );
  }

  /**
   * Creates a form to edit a TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.
   *
   * @param NyttiggjortVarme $entity The entity
   *
   * @return \Symfony\Component\Form\Form The form
   */
  private function createEditForm(NyttiggjortVarme $entity) {
    $form = $this->createForm(new NyttiggjortVarmeType(), $entity, array(
      'action' => $this->generateUrl('nyttiggjortvarme_update', array('id' => $entity->getId())),
      'method' => 'PUT',
    ));

    $form->add('submit', 'submit', array('label' => 'Update'));

    return $form;
  }

  /**
   * Edits an existing TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.
   *
   * @Route("/{id}", name="nyttiggjortvarme_update")
   * @Method("PUT")
   * @Template("AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme:edit.html.twig")
   */
  public function updateAction(Request $request, $id) {
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme')->find($id);

    if (!$entity) {
      throw $this->createNotFoundException('Unable to find TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.');
    }

    $deleteForm = $this->createDeleteForm($id);
    $editForm = $this->createEditForm($entity);
    $editForm->handleRequest($request);

    if ($editForm->isValid()) {
      $em->flush();

      return $this->redirect($this->generateUrl('nyttiggjortvarme_edit', array('id' => $id)));
    }

    return array(
      'entity' => $entity,
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
    );
  }

}
