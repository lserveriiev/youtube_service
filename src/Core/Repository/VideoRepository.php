<?php

namespace App\Core\Repository;

use App\Core\Entity\Video;
use Doctrine\ORM\EntityRepository;

class VideoRepository extends EntityRepository
{
    /**
     * @param Video $video
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getAllWithoutVideo(Video $video, int $offset = 0, int $limit = 5): array
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT v
                     FROM App\Core\Entity\Video v
                     WHERE v.id != :videoId AND v.enable=1'
            )
            ->setParameters([
                'videoId' => $video->getId()
            ])
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getResult();
    }
}
