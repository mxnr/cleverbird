<?php

namespace CleverBirdBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Repository for Lecture model.
 */
class LectureRepository extends EntityRepository
{
    /**
     * @return LectureRepository
     */
    private function getRep()
    {
        return $this->getEntityManager()->getRepository('CleverBirdBundle:Lecture');
    }

    /**
     * Find prev lecture.
     *
     * @param int $id
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getPrevLecture($id, Course $course)
    {
        $query = $this->getRep()->createQueryBuilder('l')
            ->where('l.id < :id')
            ->andWhere('l.course = :course')
            ->andWhere('l.type = :type')
            ->setParameter('id', $id)
            ->setParameter('course', $course)
            ->setParameter('type', LectureStatuses::AVAILABLE)
            ->orderBy('l.id', 'desc')
            ->setMaxResults(1);

        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * Find next entry.
     *
     * @param int $id
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getNextLecture($id, Course $course)
    {
        $query = $this->getRep()->createQueryBuilder('l')
            ->where('l.id > :id')
            ->andWhere('l.course = :course')
            ->andWhere('l.type = :type')
            ->setParameter('id', $id)
            ->setParameter('course', $course)
            ->setParameter('type', LectureStatuses::AVAILABLE)
            ->orderBy('l.id', 'asc')
            ->setMaxResults(1);

        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * Get available lectures.
     *
     * @param $courseId
     *
     * @return array
     */
    public function getAvailableLectures($courseId)
    {
        $query = $this->getRep()->createQueryBuilder('l')
            ->where('l.course = :id')
            ->andWhere('l.status = :status')
            ->setParameter('id', $courseId)
            ->setParameter('status', LectureStatuses::AVAILABLE)
            ->orderBy('l.id', 'asc');

        return $query->getQuery()->getResult();
    }
}
