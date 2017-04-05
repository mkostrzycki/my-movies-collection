<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cast
 *
 * @ORM\Table(name="cast")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CastRepository")
 */
class Cast
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
     * @ORM\Column(name="fullName", type="string", length=255, unique=true)
     */
    private $fullName;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieCastRole", mappedBy="cast")
     * @ORM\JoinTable(name="movieCastRole")
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
     * Set fullName
     *
     * @param string $fullName
     * @return Cast
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    public function getMovies()
    {

    }

    public function getMoviesByRole()
    {

    }

    /**
     * Add movieCastRoles
     *
     * @param MovieCastRole $movieCastRoles
     * @return Cast
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
