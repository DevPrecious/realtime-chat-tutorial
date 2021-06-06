<?php

namespace App\Controllers;

use App\Models\Message;
use App\Models\User;
use CodeIgniter\API\ResponseTrait;

class Home extends BaseController
{
	use ResponseTrait;
	public function index()
	{
		helper(['form']);
		$data = [];
		if($this->request->getMethod() == 'post')
		{
			$rules = [
				'username' => 'required'
			];

			if(!$this->validate($rules))
			{
				$data['validation'] = $this->validator;
			}else{
				$model = new User();
				$check_username = $model->where('username', $this->request->getVar('username'))->first();
				if($check_username){
					$userdata = [
						'username' => $check_username['username']
					];
					session()->set($userdata);
					return redirect()->to('/chat');
				}
				$save = [
					'username' => $this->request->getVar('username')
				];

				$model->save($save);
				$userdata = [
					'username' => $save['username']
				];
				session()->set($userdata);
				return redirect()->to('/chat');
			}
		}
		return view('index', $data);
	}

	public function chat()
	{
		if(!session()->get('username'))
			return redirect()->to('/');
			
		if($this->request->getMethod() == 'post')
		{
			$rules = [
				'message' => 'required'
			];

			if(!$this->validate($rules)){
				return $this->fail($this->validator->getErrors());
			}else{
				$model = new Message();
				$msg = [
					'username' => session()->get('username'),
					'message' => $this->request->getVar('message')
				];
				$model->save($msg);
				return $this->respondCreated($msg);
			}

		}
		return view('chat');
	}

	public function msg()
	{
		$model = new Message();
		$data = $model->orderBy('id', 'DESC')->findAll();
		return $this->respond($data);
	}
}
