<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoleRepository")
 */
class Role
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieCastRole", mappedBy="role")
     * @ORM\JoinTable(name="movieCastRoles")
     */
    private $movieCastRoles;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movieCastRoles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add movieCastRoles
     *
     * @param MovieCastRole $movieCastRoles
     * @return Role
     */
    public function addMovieCastRole(MovieCastRole $movieCastRoles)
    {
        $this->movieCastRoles[] = $movieCastRoles;

        return $this;
    }

    /**
     * Remove movieCastRoles
     *
     * @param MovieCastRole $movieCastRoles
     */
    public function removeMovieCastRole(MovieCastRole $movieCastRoles)
    {
        $this->movieCastRoles->removeElement($movieCastRoles);
    }

    /**
     * Get movieCastRoles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMovieCastRoles()
    {
        return $this->movieCastRoles;
    }
}
