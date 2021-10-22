<?php

namespace TwinElements\SliderBundle\Form;

use TwinElements\AdminBundle\Service\AdminTranslator;
use TwinElements\SliderBundle\Entity\Slider;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
     * @var AdminTranslator $translator
     */
    private $translator;

    public function __construct(array $twinElementsConfig, AdminTranslator $translator)
    {
        $this->translator = $translator;

            $this->defaultImageSize = $twinElementsConfig['image_size'];
            $this->mobileImageSize = $twinElementsConfig['mobile_image_size'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            ->add('orientation', ChoiceType::class, [
                'choices' => [
                    $this->translator->translate('slider.right') => Slider::Right,
                    $this->translator->translate('slider.center') => Slider::Center,
                    $this->translator->translate('slider.left') => Slider::Left
                ],
                'label' => $this->translator->translate('slider.position'),
                'label_attr' => [
                    'class' => 'col-md-2'
                ],
                'attr' => [
                    'class' => 'col-md-4 input'
                ]
            ])
            ->add('content', TinymceType::class, [
                'required' => false
            ])
            ->add('isActive', ToggleChoiceType::class)
            ->add('buttons', SaveButtonsType::class);

//        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event){
//            $slider = $event->getData();
//            $videoField = array_filter($slider->getVideo(), function ($videoValue){
//                return !empty($videoValue);
//            });
//            if(!empty($videoField) && count($videoField) !== 2){
//                $event->getForm()->addError(
//                    new FormError('Musisz wybrać dwa pliki wideo o rozszerzeniach MP4 oraz WEBM, lub zostawić obydwa pola puste')
//                );
//            }
//
//            foreach ($videoField as $videoFile){
//                $ext = pathinfo($videoFile, PATHINFO_EXTENSION);
//                if($ext !== 'webm' && $ext !== 'mp4'){
//                    $event->getForm()->addError(
//                        new FormError('Niepoprawny format pliku wideo. Dozwolone formaty - MP4 oraz WEBM')
//                    );
//                }
//            }
//
//            $slider->setVideo(empty($videoField) ? null : $videoField);
//            $event->setData($slider);
//        });
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
