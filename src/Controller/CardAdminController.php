<?php

namespace MMC\CardBundle\Controller;

use MMC\CardBundle\Model\Action;
use MMC\CardBundle\Services\CardProcessor\Request as CardProcessorRequest;
use MMC\CardBundle\Services\CardProcessor\Response as CardProcessorResponse;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CardAdminController extends CRUDController
{
    public function validateAction(Request $request)
    {
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->admin->checkAccess('validate', $object);

        if ($this->getRestMethod() == 'POST') {
            $draftItem = $object->getDraft();
            if ($draftItem) {
                $response = $this->container->get('mmc_card.CardProcessor')->execute(new CardProcessorRequest($draftItem, Action::VALIDATION));

                if ($response->getStatusCode() == CardProcessorResponse::STATUS_OK) {
                    $this->admin->getModelManager()->update($object);

                    $this->addFlash('sonata_flash_success', 'card_validation_successfully');
                } else {
                    $this->addFlash('sonata_flash_error', $response->getReasonPhrase());
                }
            } else {
                $this->addFlash('sonata_flash_error', 'card_no_draft_item');
            }

            return new RedirectResponse($this->admin->generateObjectUrl('show', $object));
        }

        return $this->render($this->admin->getTemplate('validate'), [
            'object' => $object,
            'action' => 'validate',
            'csrf_token' => $this->getCsrfToken('sonata.validate'),
        ], null);
    }

    public function validateAjaxAction(Request $request)
    {
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->admin->checkAccess('validate', $object);

        $draftItem = $object->getDraft();
        if ($draftItem) {
            $response = $this->container->get('mmc_card.CardProcessor')->execute(new CardProcessorRequest($draftItem, Action::VALIDATION));

            if ($response->getStatusCode() == CardProcessorResponse::STATUS_OK) {
                $this->admin->getModelManager()->update($object);

                return new JsonResponse(null, 200);
            } else {
                return new JsonResponse(['msg' => $response->getReasonPhrase()], 500);
            }
        }

        return new JsonResponse(['msg' => 'card_no_draft_item'], 400);
    }

    public function deleteDraftAction(Request $request)
    {
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->admin->checkAccess('validate', $object);

        if ($this->getRestMethod() == 'POST') {
            $draftItem = $object->getDraft();
            if ($draftItem) {
                $response = $this->container->get('mmc_card.CardProcessor')->execute(new CardProcessorRequest($draftItem, Action::DELETE_DRAFT));

                if ($response->getStatusCode() == CardProcessorResponse::STATUS_OK) {
                    $this->admin->getModelManager()->update($object);

                    $this->addFlash('sonata_flash_success', 'card_delete_draft_successfully');
                } else {
                    $this->addFlash('sonata_flash_error', $response->getReasonPhrase());
                }
            } else {
                $this->addFlash('sonata_flash_error', 'card_no_draft_item');
            }

            return new RedirectResponse($this->admin->generateObjectUrl('show', $object));
        }

        return $this->render($this->admin->getTemplate('delete_draft'), [
            'object' => $object,
            'action' => 'delete_draft',
            'csrf_token' => $this->getCsrfToken('sonata.delete_draft'),
        ], null);
    }

    protected function preDelete(Request $request, $object)
    {
        if (!$object->getDraft() && !$object->getValid()) {
            return new RedirectResponse($this->admin->generateUrl('list'));
        }
    }

    protected function preShow(Request $request, $object)
    {
        if (!$object->getDraft() && !$object->getValid()) {
            return new RedirectResponse($this->admin->generateUrl('list'));
        }
    }

    protected function preEdit(Request $request, $object)
    {
        if (!$object->getDraft() && !$object->getValid()) {
            return new RedirectResponse($this->admin->generateUrl('list'));
        }

        $validItem = $object->getValid();
        if ($validItem) {
            $response = $this->container->get('mmc_card.CardProcessor')->execute(new CardProcessorRequest($validItem, Action::EDIT));
        }
    }
}
