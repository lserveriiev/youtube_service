<?php

namespace App\Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="playlist")
 */
class Playlist
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="youtube_id", type="string")
     */
    protected $youtubeId;

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getYoutubeId(): string
    {
        return $this->youtubeId;
    }

    /**
     * @param string $youtubeId
     *
     * @return $this
     */
    public function setYoutubeId($youtubeId): self
    {
        $this->youtubeId = $youtubeId;

        return $this;
    }
}
