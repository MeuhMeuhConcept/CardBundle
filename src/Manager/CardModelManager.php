<?php

namespace MMC\CardBundle\Manager;

use MMC\CardBundle\Model\Action;
use MMC\CardBundle\Services\CardProcessor\CardProcessor;
use MMC\CardBundle\Services\CardProcessor\Request;
use MMC\CardBundle\Services\CardProcessor\Response;
use MMC\SonataAdminBundle\Manager\DTOManager;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CardModelManager extends DTOManager
{
    protected $cardProcessor;

    public function __construct(
        RegistryInterface $registry,
        CardProcessor $cardProcessor
    ) {
        parent::__construct($registry);

        $this->cardProcessor = $cardProcessor;
    }

    public function getModelInstance($class)
    {
        $response = $this->cardProcessor->execute(new Request($class, Action::CREATE));
        if ($response->getStatusCode() == Response::STATUS_OK) {
            return $response->getCard();
        }

        throw new \RuntimeException($response->getStatusPhrase());
    }

    public function getParentMetadataForProperty($baseClass, $propertyFullName)
    {
        list($metadata, $propertyName, $parentAssociationMappings) = parent::getParentMetadataForProperty($baseClass, $propertyFullName);

        if (preg_match('/(draft|valid)\.(.*)$/', $propertyFullName, $matches)) {
            $parentAssociationMappings[] = [
                'fieldName' => $matches[1],
            ];

            $propertyName = $matches[2];
        }

        return [$metadata, $propertyName, $parentAssociationMappings];
    }

    public function delete($object)
    {
        $response = $this->cardProcessor->execute(new Request($object, Action::DELETE_DRAFT));
        if ($response->getStatusCode() == Response::STATUS_KO) {
            throw new ModelManagerException(sprintf('Failed to delete draft version of card: %s', $response->getReasonPhrase));
        }

        $response = $this->cardProcessor->execute(new Request($object, Action::ARCHIVE));
        if ($response->getStatusCode() == Response::STATUS_KO) {
            throw new ModelManagerException(sprintf('Failed to archive valid version of card: %s', $response->getReasonPhrase));
        }

        $entityManager = $this->getEntityManager($object);
        $entityManager->persist($object);
        $entityManager->flush();
    }
}
