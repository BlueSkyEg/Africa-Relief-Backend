<?php

namespace App\Modules\User\Services;

use App\Exceptions\ApiException;
use App\Modules\User\Exceptions\UserNotFoundException;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * @return Authenticatable|null
     */
    public function getAuthUser(): ?Authenticatable
    {
        return auth('sanctum')->user();
    }

    /**
     * @param $id
     * @return User|null
     */
    public function getUserById($id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * @param string $emailOrUsername
     * @return User|null
     */
    public function getUserByEmailOrUsername(string $emailOrUsername): ?User
    {
        return $this->userRepository->findByEmailOrUsername($emailOrUsername);
    }


    /**
     * @return Collection
     * @throws UserNotFoundException
     */
    public function getUserDonations(): Collection
    {
        $user = $this->getAuthUser();

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user->donations;
    }


    /**
     * @return Collection
     * @throws UserNotFoundException
     */
    public function getUserSubscriptions(): Collection
    {
        $user = $this->getAuthUser();

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user->subscriptions;
    }


    /**
     * @param array $credentials
     * @return User
     */
    public function createUser(array $credentials): User
    {
        return $this->userRepository->create($credentials);
    }


    /**
     * @param array $info
     * @return User
     * @throws ApiException
     * @throws UserNotFoundException
     */
    public function updateUserInfo(array $info): User
    {
        $user = $this->getAuthUser();

        if (!$user) {
            throw new UserNotFoundException();
        }

        if (isset($info['email']) && $user->email !== $info['email']) {
            $info = array_merge($info, ['email_verified_at' => null]);
            $user->sendEmailVerificationNotification();
        }

        return $this->userRepository->updateInfo($user, $info);
    }

    /**
     * @param UploadedFile $image
     * @return Authenticatable
     * @throws UserNotFoundException
     */
    public function updateUserImage(UploadedFile $image): Authenticatable
    {
        $user = $this->getAuthUser();

        if (!$user) {
            throw new UserNotFoundException();
        }

        $imagePath = $image->store('users/images');

        // Check if the user already has an image before attempting to delete
        if ($user->img) {
            Storage::delete('users/images/' . $user->img);
        }

        $imageName = Str::afterLast($imagePath, '/');
        $user->img = $imageName;
        $user->save();

        return $user;
    }


    /**
     * @return Authenticatable
     * @throws UserNotFoundException
     */
    public function deleteUser(): Authenticatable
    {
        $user = $this->getAuthUser();

        if (!$user) {
            throw new UserNotFoundException();
        }

        $donor = $user->donor;
        if ($donor) {
            $donor->user_id = null;
            $donor->save();
        }

        $user->tokens()->delete();
        $user->delete();

        return $user;
    }
}
