<?php


namespace App\Data\Repositories;
use App\Data\Models\User;
//use http\Env\Request;

use Illuminate\Support\Facades\Hash;

class UserRepository
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

    public function search(){
        if($search = \Request::get('q')) {

           $user= $this->model->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhere('type', 'LIKE', "%$search%");
            })->paginate(20);

        }else{

            $user = $this->model->latest()->paginate(5);
        }
        return $user;
    }
    /**
     * @return mixed
     */
    public function display()
    {
        return $this->model->latest()->paginate(5);
    }

    /**
     * @param $request
     * @return mixed
     */
    public function create($request)
    {
        return $this->model->create([
            'name' => $request['name'],
            'email'=> $request['email'],
            'bio'=>$request['bio'],
            'type'=>$request['type'],
            'photo'=>$request['photo'],
            'password'=> Hash::make($request['password'])
             ]);
    }

    public function delete($id)
    {
        $user = $this->model->findorFail($id);
        return $user->delete();
    }

    public function update($id,$requestData)
    {

        $userId= $this->model->findorFail($id);
        return $userId->update($requestData);
    }
}
