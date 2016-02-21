<?php

namespace CleverBirdBundle\Twig;

use CleverBirdBundle\Entity\User;

class Disqus extends ApplicationExtension
{
    /**
     * @var string
     */
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'disqus_hash';
    }

    /**
     * @param User $user
     *
     * @return array
     */
    private function getData(User $user)
    {
        return [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'avatar' => $user->getAvatar(),
        ];
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function getHtml(User $user)
    {
        $message = base64_encode(json_encode($this->getData($user)));
        $timestamp = time();

        $hmac = $this->disqusHash($message.' '.$timestamp);

        return "$message $hmac $timestamp";
    }

    /**
     * @param $data
     *
     * @return string
     */
    private function disqusHash($data)
    {
        $key = $this->key;
        $blockSize = 64;
        $hashFunc = 'sha1';
        if (strlen($this->key) > $blockSize) {
            $key = pack('H*', $hashFunc($key));
        }
        $key = str_pad($key, $blockSize, chr(0x00));
        $ipad = str_repeat(chr(0x36), $blockSize);
        $opad = str_repeat(chr(0x5c), $blockSize);
        $hmac = pack(
            'H*', $hashFunc(
                ($key ^ $opad).pack(
                    'H*', $hashFunc(
                        ($key ^ $ipad).$data
                    )
                )
            )
        );

        return bin2hex($hmac);
    }
}
