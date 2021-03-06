<?php

namespace Zantolov\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Zantolov\AppBundle\Entity\Traits\ActivableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity ()
 * @ORM\Table(name="content_modules")
 * @ORM\HasLifecycleCallbacks
 */
class ContentModule
{

    use SoftDeleteableEntity;
    use TimestampableEntity;
    use ActivableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $body;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $editor = true;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return boolean
     */
    public function isEditor()
    {
        return $this->editor;
    }

    /**
     * @param boolean $editor
     */
    public function setEditor($editor)
    {
        $this->editor = $editor;
    }


}
