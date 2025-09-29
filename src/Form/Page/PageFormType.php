<?php

namespace App\Form\Page;

use App\Entity\ContentType;
use App\Entity\Category\Category;
use App\Entity\Page\Page;
use App\Form\Type\LiveAsyncFileType;
use App\Repository\Page\PageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class PageFormType extends AbstractType
{
    public function __construct(
        private PageRepository $pageRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('shortDescription', options: [
                'label' => 'Short Description',
            ])
            ->add('slug', options:[
                'required' => false
            ])
            ->add('contentType', EntityType::class, [
                'class' => ContentType::class,
                'choice_label' => 'name',
                'placeholder' => 'Select content type',
                'disabled' => true
            ])
            ->add('coverImage', LiveAsyncFileType::class)
            ->add('sliderItems', LiveCollectionType::class, [
                'entry_type' => SliderItemType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
                'label' => 'Slider items',
                'button_add_options' => [
                    'label' => 'Add new Slider Item'
                ]
            ])
            ->add('boxItems', LiveCollectionType::class, [
                'entry_type' => BoxType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
                'label' => 'Box items',
                'button_add_options' => [
                    'label' => 'Add new Box Item'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Category',
            ])
            ->add('body', options: [
                'attr' => [
                    'data-controller' => 'tinymce'
                ],
                'required' => false
            ])
            ->add('attachments', LiveAsyncFileType::class, [
                'multiple' => true
            ])
            ->add('accordionItems', LiveCollectionType::class, [
                'entry_type' => AccordionItemType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
                'label' => 'Accordion items',
                'button_add_options' => [
                    'label' => 'Add new Accordion Item'
                ]
            ])
            ->add('galleryItems', LiveAsyncFileType::class, [
                'multiple' => true,
                "help" => 'Upload a PNG/JPG file.',
                'attr' => [
                    'accept' => implode(',', [
                        'image/png',
                        'image/jpg',
                        'image/jpeg',
                        'image/webp'
                    ])
                ]
            ])
            ->add('attachments', LiveAsyncFileType::class, [
                'multiple' => true
            ])
            ->add('published', options: [
                'required' => false
            ])->add('urlRedirections', LiveCollectionType::class, [
                'entry_type' => UrlRedirectionType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
                'label' => 'Url Redirections',
                'button_add_options' => [
                    'label' => 'Add new Url Redirection'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
            'attr' => [
                'novalidate' => true,
            ]
        ]);
    }
}
