<?php

namespace CleverBirdBundle\Twig;

abstract class ApplicationExtension extends \Twig_Extension
{
    /**
     * Functions provided by extension.
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            $this->getName() => new \Twig_SimpleFunction(
                $this->getName(),
                array($this, 'getHtml'),
                array('is_safe' => array('html'))
            ),
        );
    }
}
