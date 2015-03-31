<?php

namespace Rest\AuthBundle\Entity;

use FOS\OAuthServerBundle\Entity\RefreshToken as BaseRefreshToken;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tbl_refresh_token")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks 
 */
class RefreshToken extends BaseRefreshToken
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="Rest\UserBundle\Entity\User")
     */
    protected $user;

    /**
    * @ORM\Column(type="datetime", name="created_date_time")
    */
    private $createdDateTime;

    public function getCreatedDateTime()
    {
        return $this->createdDateTime;
    }

    /** 
    * @ORM\PrePersist 
    */
    public function setCreatedDateTime()
    {
        $this->createdDateTime = new \DateTime();
    }
}

