<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Movie
 *
 * @ORM\Table(name="movie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieRepository")
 */
class Movie
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="yearOfProduction", type="smallint", nullable=true)
     */
    private $yearOfProduction;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieCastRole", mappedBy="movie")
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
     * Set title
     *
     * @param string $title
     * @return Movie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set yearOfProduction
     *
     * @param integer $yearOfProduction
     * @return Movie
     */
    public function setYearOfProduction($yearOfProduction)
    {
        $this->yearOfProduction = $yearOfProduction;

        return $this;
    }

    /**
     * Get yearOfProduction
     *
     * @return integer 
     */
    public function getYearOfProduction()
    {
        return $this->yearOfProduction;
    }

    public function getEntireCast()
    {

    }

    public function getEntireCastByRole()
    {

    }

    /**
     * Add movieCastRoles
     *
     * @param MovieCastRole $movieCastRoles
     * @return Movie
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
