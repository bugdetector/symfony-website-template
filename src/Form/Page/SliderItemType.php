<?php

namespace App\Form\Page;

use App\Entity\Page\SliderItem;
use App\Form\Type\LiveAsyncFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SliderItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('url')
            ->add('sliderImage', LiveAsyncFileType::class, [
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SliderItem::class,
        ]);
    }
}
