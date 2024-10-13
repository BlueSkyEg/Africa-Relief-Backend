<?php

namespace App\Modules\Admin\Services;

use App\Exceptions\ApiException;
use App\Modules\Admin\Exceptions\UserNotFoundException;
use App\Modules\Admin\Repositories\AdminRepository;
use App\Modules\Admin\Admin;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminService
{
    public function __construct(private readonly AdminRepository $adminRepository)
    {
    }

    /**
     * @return Authenticatable|null
     */
    public function getAuthAdmin(): ?Authenticatable
    {
        return auth('sanctum')->user();
    }

    /**
     * @param $id
     * @return Admin|null
     */
    public function getAdminById($id): ?Admin
    {
        return $this->adminRepository->find($id);
    }

    /**
     * @param string $emailOrUsername
     * @return Admin|null
     */
    public function getAdminByEmailOrUsername(string $emailOrUsername): ?Admin
    {
        return $this->adminRepository->findByEmailOrUsername($emailOrUsername);
    }


    /**
     * @param array $credentials
     * @return Admin
     */
    public function createAdmin(array $credentials): Admin
    {
        return $this->adminRepository->create($credentials);
    }


    /**
     * @param array $info
     * @return Admin
     * @throws ApiException
     * @throws UserNotFoundException
     */
    public function updateAdminInfo(array $info): Admin
    {
        $admin = $this->getAuthAdmin();

        if (!$admin) {
            throw new UserNotFoundException();
        }

        if (isset($info['email']) && $admin->email !== $info['email']) {
            $info = array_merge($info, ['email_verified_at' => null]);
            $admin->sendEmailVerificationNotification();
        }

        return $this->adminRepository->updateInfo($admin, $info);
    }

    /**
     * @param UploadedFile $image
     * @return Authenticatable
     * @throws UserNotFoundException
     */
    public function updateAdminImage(UploadedFile $image): Authenticatable
    {
        $admin = $this->getAuthAdmin();

        if (!$admin) {
            throw new UserNotFoundException();
        }

        $imagePath = $image->store('users/images');

        // Check if the admin already has an image before attempting to delete
        if ($admin->img) {
            Storage::delete('users/images/' . $admin->img);
        }

        $imageName = Str::afterLast($imagePath, '/');
        $admin->img = $imageName;
        $admin->save();

        return $admin;
    }


    /**
     * @return Authenticatable
     * @throws UserNotFoundException
     */
    public function deleteAdmin(): Authenticatable
    {
        $admin = $this->getAuthAdmin();

        if (!$admin) {
            throw new UserNotFoundException();
        }

        $admin->tokens()->delete();
        $admin->delete();

        return $admin;
    }
}
