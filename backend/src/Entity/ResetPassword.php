<?php


namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPassword
{
    /** @var string $email
     * @Assert\Email(groups={"request"})
     */
    protected $email;

    /**
     * @Assert\NotBlank(groups={"validate","reset"})
     */
    protected $token;

    /**
     * @Assert\NotBlank(groups={"reset"})
     */
    protected $password;

    /**
     * url/token
     * @Assert\NotBlank(groups={"request"})
     */
    protected $url;

    public function __construct()
    {
        $this->token = rand();
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return ?string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param ?string $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

}
