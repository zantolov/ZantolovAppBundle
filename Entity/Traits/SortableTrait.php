<?php
/**
 * Created by PhpStorm.
 * User: zoka123
 * Date: 08.09.15.
 * Time: 21:59
 */

namespace Zantolov\AppBundle\Entity\Traits;


trait SortableTrait
{
    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function moveDown()
    {
        $this->setPosition($this->getPosition() + 1);
    }

    public function moveUp()
    {
        $this->setPosition($this->getPosition() - 1);
    }

}