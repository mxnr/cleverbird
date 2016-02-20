<?php

namespace CleverBirdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseSymfonyController;

class Controller extends BaseSymfonyController
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param string $entity
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRep($entity)
    {
        return $this->getManager()->getRepository($entity);
    }

    /**
     * @param $entity
     */
    protected function save($entity)
    {
        $em = $this->getManager();
        $em->persist($entity);
        $em->flush();
    }

    /**
     * @param $entity
     */
    protected function delete($entity)
    {
        $em = $this->getManager();
        $em->remove($entity);
        $em->flush();
    }
}
