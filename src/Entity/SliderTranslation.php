<?php

namespace TwinElements\SliderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;
use TwinElements\AdminBundle\Entity\Traits\ContentInterface;
use TwinElements\AdminBundle\Entity\Traits\ContentTrait;
use TwinElements\AdminBundle\Entity\Traits\EnableInterface;
use TwinElements\AdminBundle\Entity\Traits\EnableTrait;
use TwinElements\AdminBundle\Entity\Traits\IdTrait;
use TwinElements\AdminBundle\Entity\Traits\TitleInterface;
use TwinElements\AdminBundle\Entity\Traits\TitleTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="slider_translations")
 */
class SliderTranslation implements TranslationInterface, TitleInterface, ContentInterface, EnableInterface
{
    use TranslationTrait,
        IdTrait,
        TitleTrait,
        ContentTrait,
        EnableTrait;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $redirect;
    /**
     * @var string|null
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $buttonName;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image;
    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $imageMobile;

    /**
     * @return string|null
     */
    public function getRedirect(): ?string
    {
        return $this->redirect;
    }


    public function setRedirect(?string $redirect)
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getButtonName(): ?string
    {
        return $this->buttonName;
    }


    public function setButtonName(?string $buttonName)
    {
        $this->buttonName = $buttonName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }


    public function setImage(?string $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageMobile(): ?string
    {
        return $this->imageMobile;
    }


    public function setImageMobile(?string $imageMobile)
    {
        $this->imageMobile = $imageMobile;

        return $this;
    }
}
