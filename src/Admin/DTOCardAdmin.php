<?php

namespace MMC\CardBundle\Admin;

use MMC\CardBundle\Model\Status;
use MMC\SonataAdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class DTOCardAdmin extends AbstractAdmin
{
    abstract protected function getDTOClassName();

    abstract protected function getExtraQueryFields();

    abstract protected function getItemShowFields($draft = false);

    abstract protected function getItemFormFields($draft = false);

    public function configure()
    {
        parent:: configure();

        $this->setPagerType('pager_for_group_by_query');
        unset($this->listModes['mosaic']);

        $this->setTemplate('edit', 'MMCCardBundle:Card:edit.html.twig');
        $this->setTemplate('show', 'MMCCardBundle:Card:show.html.twig');
        $this->setTemplate('validate', 'MMCCardBundle:Card:validate.html.twig');
        $this->setTemplate('delete_draft', 'MMCCardBundle:Card:delete_draft.html.twig');
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->add('validate', $this->getRouterIdParameter().'/validation');
        $collection->add('delete_draft', $this->getRouterIdParameter().'/delete_draft');
        $collection->add('validate_ajax', $this->getRouterIdParameter().'/validate', [], [], [], '', [], ['method' => 'post']);
    }

    public function getBatchActions()
    {
        return [];
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $alias = $query->getRootAlias();

        $query->innerJoin($alias.'.items', 'iv', 'WITH', 'iv.status IN (:statusValidOrCreating)')
            ->setParameter('statusValidOrCreating', [Status::VALID, Status::CREATING])
            ;

        $query->leftJoin($alias.'.items', 'id', 'WITH', 'id.status = :statusDraft')
            ->setParameter('statusDraft', [Status::DRAFT])
            ;

        $query->groupBy($alias.'.id');

        $class = $this->getDTOClassName();
        $extraQueryFields = $this->getExtraQueryFields();

        $queryChain = sprintf(
            'NEW '.$class."($alias.id, $alias.uuid, iv.status, id.id%s)",
            (count($extraQueryFields) ? ', '.implode(', ', $extraQueryFields) : '')
        );

        $query->select($queryChain);

        return $query;
    }

    public function getUrlsafeIdentifier($entity)
    {
        $class = $this->getDTOClassName();

        return
            $entity instanceof $class ?
            $entity->getId() :
            parent::getUrlsafeIdentifier($entity);
    }

    public function getNormalizedIdentifier($entity)
    {
        $class = $this->getDTOClassName();

        return
            $entity instanceof $class ?
            $entity->getId() :
            parent::getNormalizedIdentifier($entity);
    }

    public function configureShowFields(ShowMapper $showMapper)
    {
        $card = $this->getSubject();
        $validItem = $card->getValid();
        $draftItem = $card->getDraft();

        $resolver = $this->createOptionsResolverForShowMapper();

        $offsetClass = '';
        if ($validItem) {
            $showMapper
                ->with('Valid', [
                    'class' => 'col-xs-12 col-md-6',
                ]);

            foreach ($this->getItemShowFields() as $options) {
                $options = $resolver->resolve($options);

                if (!isset($options['options']['label'])) {
                    $options['options']['label'] = $options['name'];
                }

                $options['name'] = 'valid.'.$options['name'];

                $showMapper->add(
                    $options['name'],
                    $options['type'],
                    $options['options']
                );
            }
            $showMapper->end();
        } else {
            $offsetClass = 'col-md-offset-6';
        }

        if ($draftItem) {
            $showMapper
                ->with('Draft', [
                    'class' => 'col-xs-12 col-md-6 '.$offsetClass,
                    'box_class' => 'box box-warning',
                ]);

            foreach ($this->getItemShowFields(true) as $options) {
                $options = $resolver->resolve($options);

                if (!isset($options['options']['label'])) {
                    $options['options']['label'] = $options['name'];
                }

                $options['name'] = 'draft.'.$options['name'];

                $showMapper->add(
                    $options['name'],
                    $options['type'],
                    $options['options']
                );
            }
            $showMapper->end();
        }
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $card = $this->getSubject();
        $validItem = $card->getValid();
        $draftItem = $card->getDraft();

        $resolver = $this->createOptionsResolverForFormMapper();

        $offsetClass = '';
        if ($validItem) {
            $formMapper
                ->with('Valid', [
                    'class' => 'col-xs-12 col-md-6',
                ]);

            foreach ($this->getItemFormFields() as $options) {
                $options = $resolver->resolve($options);

                if (!isset($options['options']['label'])) {
                    $options['options']['label'] = $options['name'];
                }

                if (!isset($options['options']['property_path'])) {
                    $options['options']['property_path'] = 'valid.'.$options['name'];
                } elseif (!preg_match('/^valid\./', $options['options']['property_path'])) {
                    $options['options']['property_path'] = 'valid.'.$options['options']['property_path'];
                }

                $options['name'] = 'valid__'.$options['name'];

                $options['options']['disabled'] = true;

                $formMapper->add(
                    $options['name'],
                    $options['type'],
                    $options['options'],
                    $options['fieldDescriptionOptions']
                );
            }
            $formMapper->end();
        } else {
            $offsetClass = 'col-md-offset-6';
        }

        if ($draftItem) {
            $formMapper
                ->with('Draft', [
                    'class' => 'col-xs-12 col-md-6 '.$offsetClass,
                    'box_class' => 'box box-warning',
                ]);

            foreach ($this->getItemFormFields(true) as $options) {
                $options = $resolver->resolve($options);

                if (!isset($options['options']['label'])) {
                    $options['options']['label'] = $options['name'];
                }

                if (!isset($options['options']['property_path'])) {
                    $options['options']['property_path'] = 'draft.'.$options['name'];
                } elseif (!preg_match('/^draft\./', $options['options']['property_path'])) {
                    $options['options']['property_path'] = 'draft.'.$options['options']['property_path'];
                }

                $options['name'] = 'draft__'.$options['name'];

                $formMapper->add(
                    $options['name'],
                    $options['type'],
                    $options['options'],
                    $options['fieldDescriptionOptions']
                );
            }
            $formMapper->end();
        }
    }

    protected function createOptionsResolverForShowMapper()
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(['name']);
        $resolver->setDefaults([
            'type' => null,
            'options' => [],
        ]);

        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('type', ['null', 'string']);
        $resolver->setAllowedTypes('options', 'array');

        return $resolver;
    }

    protected function createOptionsResolverForFormMapper()
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(['name', 'type']);
        $resolver->setDefaults([
            'options' => [],
            'fieldDescriptionOptions' => [],
        ]);

        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('options', 'array');
        $resolver->setAllowedTypes('fieldDescriptionOptions', 'array');

        return $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessMapping()
    {
        return array_merge(parent::getAccessMapping(), [
            'validate' => 'VALIDATE',
            'delete_draft' => 'EDIT',
        ]);
    }
}
