<?php

namespace App\Form\Views;

use App\Entity\Views\Navbar;
use Arkounay\Bundle\UxCollectionBundle\Form\UxCollectionType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavbarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('url')
            ->add('icon_class');
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($builder) {
                /** @var Navbar */
                $object = $event->getData();
                if ($object && $event->getForm()->getParent()?->getParent()?->getParent() == null) {
                    $event->getForm()->add("expand_title")
                        ->add('expand_description');
                }
                if ($object) {
                    $event->getForm()->add(
                        $builder->create('children', UxCollectionType::class, [
                            'entry_type' => NavbarType::class,
                            'allow_add' => true,
                            'allow_delete' => true,
                            'allow_drag_and_drop' => true,
                            'display_sort_buttons' => true,
                            'display_insert_button' => true,
                            'add_label' => 'Add New Navbar Item',
                            'add_class' => 'btn btn-primary',
                            'insert_label' => 'Inser New Navbar Item Here',
                            'insert_class' => 'btn btn-primary',
                            'min' => 0,
                            'auto_initialize' => false,
                            'entry_options' => [
                                'label' => false
                            ]
                        ])->addModelTransformer(new CallbackTransformer(
                            function (?Collection $navbarCollection): array {
                                return $navbarCollection ? $navbarCollection->toArray() : [];
                            },
                            function (array $children): Collection {
                                return new ArrayCollection($children);
                            }
                        ))->getForm()
                    );
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Navbar::class
        ]);
    }
}
