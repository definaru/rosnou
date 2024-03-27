<?php

namespace domain\token\services;

use domain\Exception;
use domain\token\InvalidTokenException;
use Faker\Provider\DateTime;
use yii\base\Security;
use common\models\VerificationToken;
use yii\web\IdentityInterface;

class VerificationTokenService
{
    const DEFAULT_TTL = 24*60*60;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var int
     */
    private $ttl;

    public function __construct(Security $security, int $ttl = null)
    {
        $this->security = $security;
        $this->ttl = $ttl ?? self::DEFAULT_TTL;
    }

    /**
     * @param IdentityInterface $identity
     * @return VerificationToken
     */
    public function create(IdentityInterface $identity, int $tokenType)
    {
        $item = VerificationToken::findOne(['user_id' => $identity->getId(), 'type' => $tokenType]);

        if($item AND $this->justCreated($item)) {
            return $item;
        }

        VerificationToken::deleteAll(['user_id' => $identity->getId(), 'type' => $tokenType]);

        $item = new VerificationToken();
        $item->user_id = $identity->getId();
        $item->token = $this->generate();
        $item->expired = $this->expiredDateTime();
        $item->type = $tokenType;
        $item->save();

        return $item;
    }



    /**
     * @param string $token
     * @param int $type
     * @return null|VerificationToken
     */
    public function get(string $token, int $type)
    {
        $token = VerificationToken::findOne([
            'token' => $token,
            'type' => $type,
        ]);

        if(!$token) {
            throw new InvalidTokenException("Token does not exist");
        }

        if($this->isExpired($token)) {
            throw new InvalidTokenException("Token is expired");
        }

        return $token;
    }

    /**
     * @param string $token
     * @param int $type
     * @return $this
     */
    public function remove(string $token, int $type)
    {
        VerificationToken::deleteAll(['token' => $token, 'type' => $type]);

        return $this;
    }

    /**
     * @param VerificationToken $token
     * @return bool
     */
    public function isExpired(VerificationToken $token)
    {
        return (new \DateTime()) > (new \DateTime($token->expired));
    }

    /**
     * @param VerificationToken $token
     * @return bool|\DateInterval
     */
    private function justCreated(VerificationToken $token)
    {
        $diff = (new \DateTime())->getTimestamp() + self::DEFAULT_TTL - (new \DateTime($token->expired))->getTimestamp();

        return $diff < 60*5;
    }

    /**
     * @return string
     */
    private function generate()
    {
        return sha1($this->security->generateRandomString());
    }

    /**
     * @return string
     */
    private function expiredDateTime()
    {
        return (new \DateTime())->add(new \DateInterval(sprintf('PT%dS', $this->ttl)))->format('Y-m-d H:i:s');
    }
}