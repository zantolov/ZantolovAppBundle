<?php

namespace Zantolov\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Zantolov\AppBundle\Repository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Zantolov\AppBundle\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(name="users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\OneToMany(targetEntity="ApiToken", mappedBy="user")
     */
    protected $tokens;


    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    protected $gcmRegistrationId;

    /**
     * @var ApiToken
     */
    protected $activeToken;

    public function __construct()
    {
        parent::__construct();
        $this->tokens = new ArrayCollection();

    }

    /**
     * @return ApiToken
     */
    public function getActiveToken()
    {
        return $this->activeToken;
    }

    /**
     * @param ApiToken $activeToken
     */
    public function setActiveToken($activeToken)
    {
        $this->activeToken = $activeToken;
    }


    public function __toString()
    {
        return sprintf('%s (%s)', $this->getUsername(), $this->getEmail());
    }

    /**
     * @return mixed
     */
    public function getGcmRegistrationId()
    {
        return $this->gcmRegistrationId;
    }

    /**
     * @param mixed $gcmRegistrationId
     */
    public function setGcmRegistrationId($gcmRegistrationId)
    {
        $this->gcmRegistrationId = $gcmRegistrationId;
    }

}
