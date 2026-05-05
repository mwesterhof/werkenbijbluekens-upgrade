<?php

namespace App\Form\Types;

use Sulu\Bundle\FormBundle\Dynamic\FormFieldTypeConfiguration;
use Sulu\Bundle\FormBundle\Dynamic\FormFieldTypeInterface;
use Sulu\Bundle\FormBundle\Dynamic\Types\SimpleTypeTrait;
use Sulu\Bundle\FormBundle\Entity\FormField;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Source implements FormFieldTypeInterface
{
    use SimpleTypeTrait;

    private RequestStack $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): FormFieldTypeConfiguration
    {
        return new FormFieldTypeConfiguration(
            'Source (referer)',
            __DIR__ . '/../../../config/templates/form/source.xml',
            'special'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function build(FormBuilderInterface $builder, FormField $field, string $locale, array $options): void
    {
        $type = HiddenType::class;
        $options['mapped'] = true;
        $options['data'] = $this->request->getCurrentRequest()->getUri();

        $builder->add($field->getKey(), $type, $options);
    }
}
