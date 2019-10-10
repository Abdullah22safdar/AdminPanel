<?php


namespace App\Data\Repositories;
use App\Data\Models\User;
//use http\Env\Request;

use Illuminate\Support\Facades\Hash;

class ProfileRepository
{
    /**
     * @var User
     */
    protected $model;

    /**
     * UserRepository constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
         $this->model =$model;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function display()
    {
        return auth('api')->user();
    }

    /**
     * @param $request
     * @param $user
     */
    public function updateProfile($request,$user)
    {
        $currentPhoto = $user->photo;

        if ($request->photo !=$currentPhoto ) {
            $name = time().'.'.explode('/',explode(':',substr($request->photo,0,strpos($request->photo,';')))[1])[1];
            \Image::make($request->photo)->save(public_path('img/profile/').$name);

            $request->merge(['photo'=> $name]);
            $userPhoto = public_path('img/profile/'.$currentPhoto);

            if (file_exists($userPhoto))
                @unlink($userPhoto);
        }

        if(!empty($request->password))
        {
            $request->merge(['password'=> Hash::make($request['password'])]);
        }

        $user->update($request->all());
    }
}
