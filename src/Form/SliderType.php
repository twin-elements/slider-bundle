<?php

namespace TwinElements\SliderBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use TwinElements\Component\AdminTranslator\AdminTranslator;
use TwinElements\SliderBundle\Entity\ContentLocation;
use TwinElements\SliderBundle\Entity\Slider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwinElements\FormExtensions\Type\SaveButtonsType;
use TwinElements\FormExtensions\Type\TEChooseLinkType;
use TwinElements\FormExtensions\Type\TEUploadType;
use TwinElements\FormExtensions\Type\TinymceType;
use TwinElements\FormExtensions\Type\ToggleChoiceType;

class SliderType extends AbstractType
{
    /**
     * @var array|null
     */
    private $defaultImageSize;
    /**
     * @var array|null
     */
    private $mobileImageSize;

    /**
     * @var string|null
     */
    private $defaultType;

    /**
     * @var array|null
     */
    private $availableTypes;

    /**
     * @var AdminTranslator $translator
     */
    private $translator;

    public function __construct(array $twinElementsConfig, AdminTranslator $translator)
    {
        $this->translator = $translator;

        $this->defaultImageSize = $twinElementsConfig['image_size'];
        $this->mobileImageSize = $twinElementsConfig['mobile_image_size'];
        $this->defaultType = $twinElementsConfig['default_type'];

        if (count($twinElementsConfig['available_types']) === 0) {
            throw new \Exception('No available types');
        }

        foreach ($twinElementsConfig['available_types'] as $availableType) {
            try {
                $constantReflex = new \ReflectionClassConstant(Slider::class, $availableType);
                $this->availableTypes[$constantReflex->getValue()] = $availableType;
            } catch (\ReflectionException $exception) {
                continue;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $typeChoices = [];
        /**
         * @var Slider $slide
         */
        $slide = $options['data'];
        if ($slide->getType() && !array_key_exists($slide->getType(), $this->availableTypes)) {
            $slide->setType((new \ReflectionClassConstant(Slider::class, $this->defaultType))->getValue());
        }
        foreach ($this->availableTypes as $typeId => $typeName) {
            $typeChoices[$this->translator->translate('slider.type.' . $typeId)] = $typeId;
        }

        $builder
            ->add('title', TextType::class, [
                'label' => $this->translator->translate('slider.title')
            ])
            ->add('redirect', TEChooseLinkType::class, [
                'label' => $this->translator->translate('admin.redirect'),
                'help' => $this->translator->translate('admin.optional')
            ])
            ->add('buttonName', TextType::class, [
                'label' => $this->translator->translate('slider.button_name'),
                'required' => false,
                'help' => $this->translator->translate('slider.slider.button_name.optional')
            ])
            ->add('image', TEUploadType::class, [
                'file_type' => 'image',
                'label' => $this->translator->translate('admin.image'),
                'help' => $this->translator->translate('slider.recomended_picture_size', [
                    '%width%' => $this->defaultImageSize['width'],
                    '%height%' => $this->defaultImageSize['height']
                ])
            ])
            ->add('imageMobile', TEUploadType::class, [
                'file_type' => 'image',
                'label' => $this->translator->translate('slider.image_for_mobile'),
                'help' => $this->translator->translate('slider.recomended_picture_size', [
                    '%width%' => $this->mobileImageSize['width'],
                    '%height%' => $this->mobileImageSize['height']
                ])
            ])
            ->add('type', ChoiceType::class, [
                'label' => $this->translator->translate('slider.slider_type'),
                'choices' => $typeChoices
            ])
            ->add('orientation', ContentLocationType::class, [
                'choices' => []
            ])
            ->add('content', TinymceType::class, [
                'required' => false
            ])
            ->add('isActive', ToggleChoiceType::class)
            ->add('buttons', SaveButtonsType::class);

        $formModifier = function (FormInterface $form, int $type) {
            switch ($type) {
                case Slider::BackgroundImage:
                    $choices = [
                        $this->translator->translate('slider.content_location.' . ContentLocation::LeftTop) => ContentLocation::LeftTop,
                        $this->translator->translate('slider.content_location.' . ContentLocation::CenterTop) => ContentLocation::CenterTop,
                        $this->translator->translate('slider.content_location.' . ContentLocation::RightTop) => ContentLocation::RightTop,
                        $this->translator->translate('slider.content_location.' . ContentLocation::LeftCenter) => ContentLocation::LeftCenter,
                        $this->translator->translate('slider.content_location.' . ContentLocation::CenterCenter) => ContentLocation::CenterCenter,
                        $this->translator->translate('slider.content_location.' . ContentLocation::RightCenter) => ContentLocation::RightCenter,
                        $this->translator->translate('slider.content_location.' . ContentLocation::LeftBottom) => ContentLocation::LeftBottom,
                        $this->translator->translate('slider.content_location.' . ContentLocation::CenterBottom) => ContentLocation::CenterBottom,
                        $this->translator->translate('slider.content_location.' . ContentLocation::RightBottom) => ContentLocation::RightBottom
                    ];
                    break;
                default:
                    $choices = [
                        $this->translator->translate('slider.content_location.' . ContentLocation::Top) => ContentLocation::Top,
                        $this->translator->translate('slider.content_location.' . ContentLocation::Center) => ContentLocation::Center,
                        $this->translator->translate('slider.content_location.' . ContentLocation::Bottom) => ContentLocation::Bottom
                    ];
            }

            $form->add('orientation', ContentLocationType::class, [
                'choices' => $choices,
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $formModifier($event->getForm(), $data->getType());
            }
        );

        $builder->get('type')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $type = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $type);
            }
        );

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Slider::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'slider';
    }
}
