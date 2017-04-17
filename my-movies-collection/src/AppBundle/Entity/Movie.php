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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AgeCategory", inversedBy="movies")
     */
    private $ageCategory;

    /**
     * @var string
     *
     * @ORM\Column(name="runtime", type="string", length=255, nullable=true)
     */
    private $runtime;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var float
     *
     * @ORM\Column(name="imdb_rating", type="decimal", precision=3, scale=1, nullable=true)
     */
    private $imdbRating;

    /**
     * @var integer
     *
     * @ORM\Column(name="imdb_votes", type="integer", nullable=true)
     */
    private $imdbVotes;

    /**
     * @var string
     *
     * @ORM\Column(name="imdb_id", type="string", length=20, nullable=true)
     */
    private $imdbId;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieCastRole", mappedBy="movie")
     * @ORM\JoinTable(name="movieCastRole")
     */
    private $movieCastRoles;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ImdbGenre", inversedBy="movies")
     */
    private $imdbGenres;

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
        $this->imdbGenres = new ArrayCollection();
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

    /**
     * Set ageCategory
     *
     * @param AgeCategory $ageCategory
     * @return Movie
     */
    public function setAgeCategory(AgeCategory $ageCategory = null)
    {
        $this->ageCategory = $ageCategory;

        return $this;
    }

    /**
     * Get ageCategory
     *
     * @return AgeCategory
     */
    public function getAgeCategory()
    {
        return $this->ageCategory;
    }

    /**
     * Set runtime
     *
     * @param string $runtime
     * @return Movie
     */
    public function setRuntime($runtime)
    {
        $this->runtime = $runtime;

        return $this;
    }

    /**
     * Get runtime
     *
     * @return string 
     */
    public function getRuntime()
    {
        return $this->runtime;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Movie
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set imdbRating
     *
     * @param string $imdbRating
     * @return Movie
     */
    public function setImdbRating($imdbRating)
    {
        $this->imdbRating = $imdbRating;

        return $this;
    }

    /**
     * Get imdbRating
     *
     * @return string 
     */
    public function getImdbRating()
    {
        return $this->imdbRating;
    }

    /**
     * Set imdbVotes
     *
     * @param integer $imdbVotes
     * @return Movie
     */
    public function setImdbVotes($imdbVotes)
    {
        $this->imdbVotes = $imdbVotes;

        return $this;
    }

    /**
     * Get imdbVotes
     *
     * @return integer 
     */
    public function getImdbVotes()
    {
        return $this->imdbVotes;
    }

    /**
     * Set imdbId
     *
     * @param string $imdbId
     * @return Movie
     */
    public function setImdbId($imdbId)
    {
        $this->imdbId = $imdbId;

        return $this;
    }

    /**
     * Get imdbId
     *
     * @return string 
     */
    public function getImdbId()
    {
        return $this->imdbId;
    }

    /**
     * Add imdbGenres
     *
     * @param \AppBundle\Entity\ImdbGenre $imdbGenres
     * @return Movie
     */
    public function addImdbGenre(\AppBundle\Entity\ImdbGenre $imdbGenres)
    {
        $this->imdbGenres[] = $imdbGenres;

        return $this;
    }

    /**
     * Remove imdbGenres
     *
     * @param \AppBundle\Entity\ImdbGenre $imdbGenres
     */
    public function removeImdbGenre(\AppBundle\Entity\ImdbGenre $imdbGenres)
    {
        $this->imdbGenres->removeElement($imdbGenres);
    }

    /**
     * Get imdbGenres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImdbGenres()
    {
        return $this->imdbGenres;
    }
}
