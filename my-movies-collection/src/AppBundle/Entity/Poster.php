<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Poster
 *
 * @ORM\Table(name="poster")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PosterRepository")
 *
 * @Vich\Uploadable()
 */
class Poster
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
     * @var File
     *
     * @Vich\UploadableField(mapping="poster", fileNameProperty="name", size="size")
     */
    private $file;

    /**
     * @var integer
     *
     * @ORM\Column(name="size", type="integer", nullable=true)
     */
    private $size;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie", inversedBy="posters")
     */
    private $movie;


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
     * Set movie
     *
     * @param Movie $movie
     * @return Poster
     */
    public function setMovie(Movie $movie = null)
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * Get movie
     *
     * @return Movie
     */
    public function getMovie()
    {
        return $this->movie;
    }


    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     * @return Poster
     */
    public function setFile(File $image = null)
    {
        $this->file = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $name
     *
     * @return Poster
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param integer $size
     *
     * @return Poster
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Add poster file from URL
     *
     * @param string $url
     */
    public function getFileFromUrl($url)
    {
        $posterImgFile = file_get_contents($url);

        $posterFileName = uniqid() . '.jpg';

        file_put_contents($this->getUploadRootDir() . $posterFileName, $posterImgFile);

        $this->setName($posterFileName);

        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * Get absolute path to posters directory
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    /**
     * Get inner path (in web folder) with movie posters
     *
     * @return string
     */
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'images/posters/';
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Poster
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
