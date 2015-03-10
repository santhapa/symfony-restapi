<?php

namespace Rest\AuthBundle\Entity;

use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tbl_client")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks 
 */
class Client extends BaseClient
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }

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

