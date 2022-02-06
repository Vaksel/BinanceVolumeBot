<?php

namespace App\Http\Controllers;

use App\Models\SavedPairs;
use http\Env\Response;
use Illuminate\Http\Request;
use Itstructure\GridView\DataProviders\EloquentDataProvider;

use App\Models\Profile;

class ProfileController extends Controller
{

//
//    public function view_token(Request $request)
//    {
//        if(!empty($request->id))
//        {
//            $token_record = Profile::find($request->id);
//
//            if(!empty($token_record))
//            {
//                $res = ['success' => true, 'token' => $token_record->token_value];
//            }
//            else
//            {
//                $res = ['success' => false, 'error' => 'неизвестная ошибка сервера'];
//            }
//
//            return response()->json($res);
//        }
//
//        $res = ['success' => false, 'error' => 'не был указан id токена, переход не с таблицы токенов воспрещен!'];
//
//        return response()->json($res);
//    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response Ответ который вмещает в себя статус, сообщение и поля профиля
     *
     */
    public function load_profile(Request $request)
    {
        $profile_id = $request->profile_id ?? $request->cookie('chosenProfile') ?? 0;

        if ($profile_id) {
            try
            {
                $profile = Profile::where(['id' => $profile_id])->first();
            }
            catch (\Exception $e)
            {
                return response($this->responseHelper('error', $e));
            }

            $savedPairs = $profile->pairs;

            return response(['status' => 'success', 'msg' => 'Пары были получены', 'pairs' => $savedPairs]);
        }

        return response(['status' => 'fail', 'msg' => 'Профиль найден не был']);
    }

    public function edit_profile(Request $request)
    {
        if(!empty($request->id))
        {
            $profile_record = Profile::find($request->id);

            if(!empty($profile_record))
            {
                return view('profile/edit', compact('token_record'));
            }
            else
            {
                abort(406);
            }
        }

        abort(404);

    }

    public function change_profile(Request $request)
    {

        if(!empty($request->input('profile_id')))
        {
            $id = $request->input('profile_id');
        }
        else
        {
            return Response($this->responseHelper('warning', 'Не был указан id профиля'));
        }

        if(!empty($request->input('profile_name')))
        {
            $name = $request->input('profile_name');
        }
        else
        {
            return Response($this->responseHelper('warning', 'Не было указано имя профиля'));
        }

        try
        {
            $profile = Profile::find($id);

            $profile->name = $name;

            $profile->save();

            return Response($this->responseHelper('success', 'Профиль был успешно изменен'));
        }
        catch (\Exception $e)
        {
            return Response($this->responseHelper('error', 'Профиль не был изменен, возникла ошибка сервера, вывод в консоли', ['error' => $e]));
        }

    }

    public function delete_profile(Request $request)
    {
        if(empty($request->profile_id))
        {
            return Response($this->responseHelper('error', 'Не был указан id профиля, перезагрузите страницу и попробуйте снова'));
        }

        $profile_id = $request->profile_id;

        try
        {
            Profile::find($profile_id)->delete();
            return Response($this->responseHelper('success', 'Профиль был успешно удален', ['profile_id' => $profile_id]));
        }
        catch (\Exception $e)
        {
            return Response($this->responseHelper('error', 'Профиль не был удален, произошла ошибка сервера', ['profile_id' => $profile_id, 'error' => $e]));
        }

    }


    /**
     * @param Request $request->name
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function add_profile(Request $request)
    {
        $validatedData = $request->validate([
            'profile_name'          => ['required', 'string', 'max:255'],
        ]);

//        return Response($this->responseHelper('check', 123));

        try
        {
            $profile = new Profile();
            $profile->name = $request->profile_name;

            $profile->save();

            $profile_id = $profile->id;

            if(!empty($request->profile_pairs))
            {
                $this->save_profile_pairs($request->profile_pairs, $profile_id);
            }
        }
        catch(\Exception $e)
        {
            return Response($this->responseHelper('error', 'Произошла ошибка, вывод в консоли', ['error' => $e]));
        }

        return Response($this->responseHelper('success', 'Профиль был успешно записан',['profile_info' => ['profile_id' => $profile->id, 'profile_name' => $profile->name]]));
    }

    protected function save_profile_pairs($pairs, $profile_id)
    {
        foreach ($pairs as $pair)
        {
            $model = new SavedPairs();

            $model->crypto_pair = $pair['crypto_pair'];
            $model->time_frame = $pair['time_frame'];
            $model->compare_volume = $pair['compare_volume'];
            $model->choosen = $pair['choosen'];
            $model->profile_id = $profile_id;

            $model->save();
        }

    }
}
