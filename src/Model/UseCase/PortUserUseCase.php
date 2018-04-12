<?php
/**
 * Created by PhpStorm.
 * User: oriol
 * Date: 12/4/2018
 * Time: 20:24
 */

namespace PwBox\Model\UseCase;


use PwBox\Model\User;
use PwBox\Model\UserRepository;

class PortUserUseCase
{
    /** @var UserRepository */
    private $repository;

    /**
     * PortUserUseCase constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke(array $rawData)
    {
        $now = new \DateTime('now');
        $user = new User(
            null,
            $rawData['username'],
            $rawData['email'],
            $rawData['password'],
            $now,
            $now
        );
        $this->repository->save($user);
    }

}