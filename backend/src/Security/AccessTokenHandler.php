<?php
// src/Security/AccessTokenHandler.php
namespace App\Security;


use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class AccessTokenHandler
{
    private JWTEncoderInterface $JWTEncoder;
    private UserRepository $userRepository;

    public function __construct(
        JWTEncoderInterface $JWTEncoder,
        UserRepository      $userRepository
    )
    {
        $this->JWTEncoder = $JWTEncoder;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws JWTDecodeFailureException|NonUniqueResultException
     */
    public function getUserBadgeFrom(Request $request): \App\Entity\User
    {
        $bearerToken = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $bearerToken);

        $accessToken = $this->JWTEncoder->decode($token);
        $userEmail = $accessToken['username'];
        $user = $this->userRepository->findOneByEmail($userEmail);
        if (!$user) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        return $user;
    }
}