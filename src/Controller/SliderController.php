<?php

namespace TwinElements\SliderBundle\Controller;

use Doctrine\Persistence\ManagerRegistry;
use TwinElements\Component\CrudLogger\CrudLogger;
use TwinElements\Component\ResponseParameterBuilder\ResponseParameterBuilder;
use TwinElements\SortableBundle\Entity\PositionInterface;
use TwinElements\SliderBundle\Form\SliderType;
use TwinElements\AdminBundle\Model\CrudControllerTrait;
use TwinElements\SliderBundle\Entity\Slider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use TwinElements\SliderBundle\Security\SliderVoter;
use TwinElements\SortableBundle\SortableResponseParametersPreparer;

/**
 * @Route("slider")
 */
class SliderController extends AbstractController
{

    use CrudControllerTrait;

    /**
     * @Route("/", name="slider_index", methods={"GET"})
     */
    public function indexAction(
        Request         $request,
        ManagerRegistry $managerRegistry
    )
    {
        try {
            $this->denyAccessUnlessGranted(SliderVoter::VIEW, new Slider());
            $em = $managerRegistry->getManager();

            $this->breadcrumbs->setItems([
                $this->adminTranslator->translate('slider.slider_list') => null
            ]);

            $responseParameters = new ResponseParameterBuilder();
            $responseParameters->addParameter('slides', $em->getRepository(Slider::class)->findIndexListItems($request->getLocale()));
            SortableResponseParametersPreparer::prepare($responseParameters, Slider::class);

            return $this->render('@TwinElementsSlider/index.html.twig', $responseParameters->getParameters());
        } catch (\Exception $exception) {
            $this->flashes->errorMessage($exception);
            return $this->redirectToRoute('admin_dashboard');
        }
    }

    /**
     * @Route("/new", name="slider_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $slider = new Slider();
        $this->denyAccessUnlessGranted(SliderVoter::FULL, $slider);

        $slider->setCurrentLocale($request->getLocale());

        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            return $this->render('@TwinElementsSlider/form.html.twig', [
                'form' => $form->createView()
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();

                $em->persist($slider);

                $slider->mergeNewTranslations();

                $em->flush();

                $this->crudLogger->createLog(Slider::class, CrudLogger::CreateAction, $slider->getId());

                $this->flashes->successMessage($this->adminTranslator->translate('admin.success_operation'));;
            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception->getMessage());
                return $this->redirectToRoute('slider_index');
            }

            if ('save' === $form->getClickedButton()->getName()) {
                return $this->redirectToRoute('slider_edit', array('id' => $slider->getId()));
            } else {
                return $this->redirectToRoute('slider_index');
            }
        }

        $this->breadcrumbs->setItems([
            $this->adminTranslator->translate('slider.slider_list') => $this->generateUrl('slider_index'),
            $this->adminTranslator->translate('slider.create_new_slide') => null
        ]);

        return $this->render('@TwinElementsSlider/new.html.twig', array(
            'slider' => $slider,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="slider_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Slider $slider)
    {

        $this->denyAccessUnlessGranted(SliderVoter::EDIT, $slider);

        $deleteForm = $this->createDeleteForm($slider);
        $editForm = $this->createForm(SliderType::class, $slider);
        $editForm->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            return $this->render('@TwinElementsSlider/form.html.twig', [
                'form' => $editForm->createView()
            ]);
        }

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();

                $slider->mergeNewTranslations();

                $em->flush();

                $this->crudLogger->createLog(Slider::class, CrudLogger::EditAction, $slider->getId());

                $this->flashes->successMessage($this->adminTranslator->translate('admin.success_operation'));;
            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception->getMessage());
            }

            if ('save' === $editForm->getClickedButton()->getName()) {
                return $this->redirectToRoute('slider_edit', array('id' => $slider->getId()));
            } else {
                return $this->redirectToRoute('slider_index');
            }
        }

        $this->breadcrumbs->setItems([
            $this->adminTranslator->translate('slider.slider_list') => $this->generateUrl('slider_index'),
            $slider->getTitle() => null
        ]);

        return $this->render('@TwinElementsSlider/edit.html.twig', array(
            'entity' => $slider,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="slider_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Slider $slider)
    {
        $this->denyAccessUnlessGranted(SliderVoter::FULL, $slider);

        $form = $this->createDeleteForm($slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $id = $slider->getId();
                $title = $slider->getTitle();

                $em = $this->getDoctrine()->getManager();

                $em->remove($slider);
                $em->flush();

                $this->crudLogger->createLog(Slider::class, CrudLogger::DeleteAction, $id);

                $this->flashes->successMessage($this->adminTranslator->translate('admin.success_operation'));;

            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception->getMessage());
            }

        }

        return $this->redirectToRoute('slider_index');
    }


    /**
     * @param Slider $slider The slider entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Slider $slider)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('slider_delete', array('id' => $slider->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
