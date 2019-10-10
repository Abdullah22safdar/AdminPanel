<?php

namespace App\Http\Controllers\API;

use App\Data\Repositories\UserRepository;
use App\Http\Controllers\ApiBaseController;
use App\Data\Models\User;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class UserApiController extends ApiBaseController
{
    /**
     * @var UserRepository
     */
    protected $userRepository;


    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository=$userRepo;
       //$this->middleware('auth:api');
        //$this->authorize('isAdmin');
    }


    /**
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $this->authorize('isAdmin');
            $user = $this->userRepository->display();

            if ($user)
            {
                return $this->sendSuccess(200, $user->toArray(), 'Users fetched Successfully');
            } else {
                return $this->getMessageBag()->sendError(404, $user->toArray(), 'Users not found');
            }
        }
        catch (Exception $exception)
        {
            return $this->sendError(500, [], $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|min:6'
            ]);

            $user = $this->userRepository->create($request);
            if ($user) {
                return $this->sendSuccess(200, $user->toArray(), ['User create Successfully']);
            } else {
                return $this->sendSuccess(500, $user->toArray(), ['User can not be created']);
            }
        }
        catch (BadRequestHttpException $exception)
        {
            return $this->sendSuccess(500, [], $exception->getMessage());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        $users =  $this->userRepository->search();
        return $users;

    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        try
        {
            $requestData = $request->all();
            $userId = $this->userRepository->findById($id);
            $this->validate($request, [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email,' . $userId->id,
                'password' => 'sometimes|min:6'
            ]);

            if ($userId) {
                $user = $this->userRepository->update($id, $requestData);

                if ($user)
                    return $this->sendSuccess(200, [], ['User Updated Successfully']);
                return $this->sendSuccess(500, [], ['User can not be updated']);
            }
        }
        catch (BadRequestHttpException $exception)
        {
            return $this->sendSuccess(500, [], $exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try
        {
            $this->authorize('isAdmin');

            $userId = $this->userRepository->findById($id);

            if ($userId)
            {
                $user = $this->userRepository->delete($id);

                if ($user)
                {
                    return $this->sendSuccess(200, [], ['User Deleted Successfully']);
                } else
                    return $this->sendError(500, [], 'User not deleted');
            }
            return $this->sendError(404, [], 'User not found');
        }
        catch (Exception $exception)
        {
            return $this->sendError(500, [], $exception->getMessage());
        }
    }
}
