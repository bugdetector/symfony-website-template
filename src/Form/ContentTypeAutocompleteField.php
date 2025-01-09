<?php

namespace App\Form;

use App\Entity\ContentType;
use App\Repository\ContentTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class ContentTypeAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => ContentType::class,
            'placeholder' => 'Choose a Content Type',
            'choice_label' => 'name',
            'query_builder' => function (ContentTypeRepository $contentTypeRepository) {
                return $contentTypeRepository->createQueryBuilder('contentType');
            },
            'security' => 'ROLE_ADMIN',
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
