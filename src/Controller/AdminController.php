<?php


namespace Login\App\Controller;


use Exception;
use Login\App\Domain\Model\User;
use Login\App\Infrastructure\Repository\PdoUserRepository;

class AdminController
{
    public function registerValidation(string $email, string $password)
    {
        $this->validEmail($email);
        $this->validPassword($password);

        $user = new User();
        $result = new PdoUserRepository();

        $user->setEmail($email);
        $user->setPassword($password);

        if ($result->isUserAlreadyRegistered($user)) {
            throw new Exception('User already exists');
        }

        $result->save($user);

    }

    public function deleteValitadion($id)
    {
        $this->validId($id);

        $user = new User();
        $result = new PdoUserRepository();

        $user->setId($id);

        $result->remove($user);
    }

    public function switchStatusUser($id, $active)
    {
        $this->validId($id);

        $user = new User();
        $result = new PdoUserRepository();

        $user->setId($id);
        $user->setActive($active);

        $result->update($user);
    }

    public function validEmail($email): void
    {
        $result = filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($result === false) {
            throw new Exception('Please, insert email valid');
        }
    }

    public function validPassword($password): void
    {
        $result = trim(filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));

        if (!$result) {
            throw new Exception('Please, insert password valid');
        } elseif (strlen($result) < 3) {
            throw new Exception('Password must to be longer three characters');
        }
    }

    public function validId($id): void
    {
        $result = filter_var($id, FILTER_VALIDATE_INT);
        if ($result === false or $result <= 0) {
            throw new Exception('ID is invalid');
        }
    }
}