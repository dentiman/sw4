<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\ResetPasswordRequest;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;


class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
    }

    public function request(ResetPassword  $data, MailerInterface $mailer, ValidatorInterface $validator): ResetPassword
    {
        if(!$data->getUrl()) {
            throw new BadRequestHttpException('Url is required!');
        }
        if(!$data->getEmail()) {
            throw new BadRequestHttpException('Email is required!');
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'email' => $data->getEmail(),
        ]);


        // Do not reveal whether a user account was found or not.
        if (!$user) {
            throw new BadRequestHttpException('User not found');
        }

        $resetToken = $this->resetPasswordHelper->generateResetToken($user);
//        try {
//            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
//        } catch (ResetPasswordExceptionInterface $e) {
//
//            throw new BadRequestHttpException($e->getMessage());
//        }

        $email = (new TemplatedEmail())
            ->from(new Address('support@stock-watcher.com', 'Support'))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
                'url' => 'https://' . str_replace(['http://','https://'],'',$data->getUrl()) ,
                'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
            ])
        ;

        $mailer->send($email);

       return $data;
    }


    public function validate(ResetPassword  $data): ResetPassword
    {
        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser( $data->getToken());
        } catch (ResetPasswordExceptionInterface $e) {
            throw new BadRequestHttpException( sprintf(
                'There was a problem validating your reset request - %s',
                $e->getReason()
            ));
        }
        return $data;
    }

    public function reset(ResetPassword  $data, UserPasswordHasherInterface $passwordHasher): ResetPassword
    {
        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser( $data->getToken());
        } catch (ResetPasswordExceptionInterface $e) {
            throw new BadRequestHttpException( sprintf(
                'There was a problem validating your reset request - %s',
                $e->getReason()
            ));
        }
        $this->resetPasswordHelper->removeResetRequest($data->getToken());

        // Encode the plain password, and set it.
        $encodedPassword = $passwordHasher->hashPassword(
            $user,
            $data->getPassword()
        );

        $user->setPassword($encodedPassword);
        $this->getDoctrine()->getManager()->flush();
        return $data;
    }

}
