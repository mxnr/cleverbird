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
        return [
            $this->getName() => new \Twig_SimpleFunction(
                $this->getName(),
                [$this, 'getHtml'],
                ['is_safe' => ['html']]
            ),
        ];
    }
}
