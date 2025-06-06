<?php

namespace ACTCMS\SchoolService\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ACTCMS\SchoolService\Repositories\InstallRepository;
use ACTCMS\Service\Repositories\InstallRepository as ServiceRepository;
use ACTCMS\SchoolService\Requests\UserRequest;
use Illuminate\Support\Facades\Storage;

class InstallController extends Controller{
    protected $repo, $request, $service_repo;

    public function __construct(
        InstallRepository $repo,
        Request $request,
        ServiceRepository $service_repo
    )
    {
        $this->repo = $repo;
        $this->request = $request;
        $this->service_repo = $service_repo;
    }

    public function index(){

        $this->service_repo->checkInstallation();
        return view('school::install.welcome');
    }


    public function user(){
        $ac = Storage::exists('.temp_app_installed') ? Storage::get('.temp_app_installed') : null;

        if(!$this->service_repo->checkDatabaseConnection() || !$ac){
            abort(404);
        }

		return view('school::install.user');
    }

    public function post_user(UserRequest $request){
      
        $this->service_repo->install($request->all());
        $this->repo->install($request->all());
		return response()->json(['message' => __('school::install.done_msg'), 'goto' => route('service.done')]);
    }


}
