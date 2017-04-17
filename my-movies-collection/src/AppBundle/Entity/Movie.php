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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Poster", mappedBy="movie")
     */
    private $posters;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movieCastRoles = new ArrayCollection();
        $this->posters = new ArrayCollection();
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

    /**
     * Add movieCastRole
     *
     * @param MovieCastRole $movieCastRole
     * @return Movie
     */
    public function addMovieCastRole(MovieCastRole $movieCastRole)
    {
        $this->movieCastRoles[] = $movieCastRole;

        return $this;
    }

    /**
     * Remove movieCastRole
     *
     * @param MovieCastRole $movieCastRole
     */
    public function removeMovieCastRole(MovieCastRole $movieCastRole)
    {
        $this->movieCastRoles->removeElement($movieCastRole);
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

    /**
     * Add poster
     *
     * @param Poster $poster
     * @return Movie
     */
    public function addPoster(Poster $poster)
    {
        $this->posters[] = $poster;

        return $this;
    }

    /**
     * Remove poster
     *
     * @param Poster $poster
     */
    public function removePoster(Poster $poster)
    {
        $this->posters->removeElement($poster);
    }

    /**
     * Get posters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosters()
    {
        return $this->posters;
    }
}
