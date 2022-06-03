<?php

namespace TwinElements\SliderBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use TwinElements\AdminBundle\Entity\Traits\IdTrait;
use TwinElements\SortableBundle\Entity\PositionInterface;
use TwinElements\SortableBundle\Model\PositionTrait;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\BlameableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\LoggableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Blameable\BlameableTrait;
use Knp\DoctrineBehaviors\Model\Loggable\LoggableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass="TwinElements\SliderBundle\Repository\SliderRepository")
 * @ORM\Table(name="slider")
 */
class Slider implements TranslatableInterface, BlameableInterface, TimestampableInterface, LoggableInterface, PositionInterface
{
    use IdTrait,
        TranslatableTrait,
        BlameableTrait,
        TimestampableTrait,
        PositionTrait,
        LoggableTrait;

    const BackgroundImage = 1;
    const ImageLeft = 2;
    const ImageRight = 3;

    /**
     * @var integer|null
     * @ORM\Column(type="smallint")
     */
    private $type = self::BackgroundImage;

    /**
     * @var int|null
     * @ORM\Column(name="orientation", type="smallint")
     * @Assert\NotBlank()
     */
    private $orientation = ContentLocation::LeftCenter;

    /**
     * @return int|null
     */
    public function getOrientation(): ?int
    {
        return $this->orientation;
    }

    /**
     * @param int|null $orientation
     */
    public function setOrientation(?int $orientation): void
    {
        $this->orientation = $orientation;
    }

    public function isActive()
    {
        return $this->translate(null, false)->isEnable();
    }

    public function setIsActive($isActive)
    {
        $this->translate(null, false)->setEnable($isActive);

        return $this;
    }

    public function isEnable()
    {
        return $this->translate(null, false)->isEnable();
    }

    public function setEnable(bool $enable)
    {
        $this->translate(null, false)->setEnable($enable);

        return $this;
    }

    public function getTitle()
    {
        return $this->translate(null, false)->getTitle();
    }

    public function setTitle($title)
    {
        $this->translate($this->currentLocale, false)->setTitle($title);

        return $this;
    }

    public function getRedirect()
    {
        return $this->translate(null, false)->getRedirect();
    }

    public function setRedirect($redirect)
    {
        $this->translate($this->currentLocale, false)->setRedirect($redirect);

        return $this;
    }

    public function getButtonName()
    {
        return $this->translate(null, false)->getButtonName();
    }

    public function setButtonName($buttonName)
    {
        $this->translate($this->currentLocale, false)->setButtonName($buttonName);

        return $this;
    }

    public function getImage()
    {
        return $this->translate(null, false)->getImage();
    }

    public function setImage($image)
    {
        $this->translate($this->currentLocale, false)->setImage($image);

        return $this;
    }

    public function getImageMobile()
    {
        return $this->translate(null, false)->getImageMobile();
    }

    public function setImageMobile($imageMobile)
    {
        $this->translate($this->currentLocale, false)->setImageMobile($imageMobile);

        return $this;
    }

    public function getContent()
    {
        return $this->translate(null, false)->getContent();
    }

    public function setContent($content)
    {
        $this->translate($this->currentLocale, false)->setContent($content);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param int|null $type
     */
    public function setType(?int $type): void
    {
        $this->type = $type;
    }
}
