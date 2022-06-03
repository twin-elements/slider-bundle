<?php

namespace TwinElements\SliderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwinElements\Component\AdminTranslator\AdminTranslator;

class ContentLocationType extends AbstractType
{
    /**
     * @var AdminTranslator $translator
     */
    private $translator;

    public function __construct(AdminTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => $this->translator->translate('slider.position')
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
