<?php

namespace TwinElements\SliderBundle\Repository;

use TwinElements\SliderBundle\Entity\Slider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class SliderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Slider::class);
    }

    /**
     * @return Slider[]
     * @throws Exception
     */
    public function findIndexListItems(string $locale)
    {
        if (is_null($locale)) {
            throw new Exception();
        }

        $qb = $this->createQueryBuilder('slider');

        $qb
            ->select(['slider', 'slider_translations'])
            ->leftJoin('slider.translations', 'slider_translations')
            ->orderBy('slider.position', 'asc');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Slider[]
     * @throws Exception
     */
    public function findAllActive(string $locale)
    {
        if (is_null($locale)) {
            throw new Exception();
        }

        $qb = $this->createQueryBuilder('slider');

        $qb
            ->select(['slider', 'slider_translations'])
            ->leftJoin('slider.translations', 'slider_translations')
            ->where(
                $qb->expr()->eq('slider_translations.locale', ':locale')
            )
            ->setParameter('locale', $locale)
            ->andWhere(
                $qb->expr()->eq('slider_translations.enable', ':enable')
            )
            ->setParameter('enable', true)
            ->orderBy('slider.position', 'asc');

        return $qb->getQuery()->getResult();
    }
}
