<?php

namespace App\Services;

use App\Models\User as LaravelUser;
use Generated\User\UserServiceInterface as GrpcUserServiceInterface;
use Generated\User\User as GrpcUser;
use Generated\User\GetUsersResponse as GrpcGetUsersResponse;
use Generated\User\DeleteUserRequest as GrpcDeleteUserRequest;
use Generated\User\DeleteUserResponse as GrpcDeleteUserResponse;

class UserService implements GrpcUserServiceInterface
{
    /**
     * Get all users
     *
     * @param \Spiral\RoadRunner\GRPC\ContextInterface $ctx
     * @param \Google\Protobuf\GPBEmpty $in
     * @return GrpcGetUsersResponse
     */
    public function GetUsers(\Spiral\RoadRunner\GRPC\ContextInterface $ctx, \Google\Protobuf\GPBEmpty $in): GrpcGetUsersResponse
    {
        $users = LaravelUser::all();
        $response = new GrpcGetUsersResponse();

        foreach ($users as $user) {
            $userProto = new GrpcUser();
            $userProto->setId($user->id);
            $userProto->setName($user->name);
            $userProto->setEmail($user->email);
            $response->getUsers()[] = $userProto;
        }

        return $response;
    }

    /**
     * Create a new user
     *
     * @param \Spiral\RoadRunner\GRPC\ContextInterface $ctx
     * @param GrpcUser $in
     * @return GrpcUser
     */
    public function CreateUser(\Spiral\RoadRunner\GRPC\ContextInterface $ctx, GrpcUser $in): GrpcUser
    {
        $user = new LaravelUser();
        $user->name = $in->getName();
        $user->email = $in->getEmail();
        $user->password = bcrypt('password'); // Default password, should be handled differently in production
        $user->save();

        $response = new GrpcUser();
        $response->setId($user->id);
        $response->setName($user->name);
        $response->setEmail($user->email);

        return $response;
    }

    /**
     * Update a user
     *
     * @param \Spiral\RoadRunner\GRPC\ContextInterface $ctx
     * @param GrpcUser $in
     * @return GrpcUser
     */
    public function UpdateUser(\Spiral\RoadRunner\GRPC\ContextInterface $ctx, GrpcUser $in): GrpcUser
    {
        $user = LaravelUser::find($in->getId());

        if ($user) {
            $user->name = $in->getName();
            $user->email = $in->getEmail();
            $user->save();
        }

        $response = new GrpcUser();
        $response->setId($user->id);
        $response->setName($user->name);
        $response->setEmail($user->email);

        return $response;
    }

    /**
     * Delete a user
     *
     * @param \Spiral\RoadRunner\GRPC\ContextInterface $ctx
     * @param GrpcDeleteUserRequest $in
     * @return GrpcDeleteUserResponse
     */
    public function DeleteUser(\Spiral\RoadRunner\GRPC\ContextInterface $ctx, GrpcDeleteUserRequest $in): GrpcDeleteUserResponse
    {
        $userId = $in->getId();

        $user = LaravelUser::find($userId);
        $response = new GrpcDeleteUserResponse();

        if ($user) {
            $user->delete();
            $response->setSuccess(true);
        } else {
            $response->setSuccess(false);
        }

        return $response;
    }
}
