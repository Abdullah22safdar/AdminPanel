<?php

namespace App\Http\Controllers\API;

use App\Data\Repositories\ProfileRepository;
use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use Exception;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class ProfileApiController extends ApiBaseController
{
    /**
     * @var ProfileRepository
     */
    protected $profileRepository;

    /**
     * ProfileApiController constructor.
     * @param ProfileRepository $profileRepo
     */
    public function __construct(ProfileRepository $profileRepo)
    {
        $this->profileRepository = $profileRepo;

    }

    /**
     * @return mixed
     */
    public function show()
    {
        try {
            $user = $this->profileRepository->display();
            return $this->sendSuccess(200, $user->toArray(), 'Profile fetched Successfully');
        }
        catch (Exception $exception)
        {
            return $this->sendError(500, [], $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $user = auth('api')->user();
            $this->validate($request, [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email,' . $user->id,
                'password' => 'sometimes|min:6'
            ]);
            $this->profileRepository->updateProfile($request, $user);
            return $this->sendSuccess(200, $user->toArray(), 'Profile updated Successfully');
        }
        catch (Exception $exception)
        {
            return $this->sendError(500, [], $exception->getMessage());
        }
    }

}
