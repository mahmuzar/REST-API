<?php

/**
 * Контроллер рецептоп
 * 
 * Четыре метода:
 *      1. create - создание рецепта. POST - метод
 *      2. read - чтение всех рецептов пользователя. GET-метод
 *      3. update - обновление конкретного рецепта по его id. Put - метод
 *      4. delete - удаление рецепта по его id. DELETE-метод
 *  
 * В любом случае в каждом методе выбрасывается исключение:
 *      1. SuccessException - успешно выполнен запрос
 *      2. ErrorException - ошибка запрос.
 * Все исключения перехватываются в Hendler, и с соответствующим заголовком, статусом и 
 * сообщением об ошибке отдается json клиенту.
 * 
 */

namespace App\Api\Http\Controllers;

use Validator;
use \Illuminate\Http\Request;
use App\Api\Recipe;
use App\Api\Rules\Required;
use Illuminate\Http\Response;
use App\Api\Exceptions\ErrorException;
use App\Api\Exceptions\SuccessException;

class RecipeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
                    'image' => ['required', new Required()],
                    'title' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ErrorException(json_encode($validator->errors()), Response::HTTP_PARTIAL_CONTENT);
        }

        $recipe = new Recipe();
        $recipe->user_id = $request->user('api')->id;
        $recipe->title = $request->title;
        $recipe->description = $request->description;
        $recipe->image = $request->image;

        if ($recipe->save()) {
            throw new SuccessException(trans('recipe.created', ['id' => $recipe->id]), Response::HTTP_CREATED);
        }
        throw new ErrorException(trans('recipe.not_created'), Response::HTTP_PARTIAL_CONTENT);
    }

    public function read(Request $request) {
        $recipe = $request->user('api')->recipe;
        return response()->json(
                        ["ok" => ['status' => Response::HTTP_OK,
                        'content' => $recipe->all()]], Response::HTTP_OK
        );
    }

    public function update(Request $request) {

        $recipe = Recipe::find($request->id);
        if (!empty($recipe)) {
            $recipe->user_id = $request->user('api')->id;
            $recipe->title = $request->title;
            $recipe->description = $request->description;
            $recipe->image = $request->image;
            if ($recipe->update()) {
                throw new SuccessException(trans('recipe.updated', ['id' => $request->id]), Response::HTTP_OK);
            }
        }
        throw new ErrorException(trans('recipe.not_exist', ['id' => $request->id]), Response::HTTP_NOT_FOUND);
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
                    'id' => ['required', 'int', new Required()],
        ]);
        if ($validator->fails()) {
            throw new ErrorException(json_encode($validator->errors()), Response::HTTP_NOT_FOUND);
        }
        $recipe = Recipe::where('id', $request->id)->where('user_id', $request->user('api')->id);

        if (!empty($recipe)) {
            if ($recipe->delete()) {
                throw new SuccessException(trans('recipe.deleted', ['id' => $request->id]), Response::HTTP_OK);
            }
        }
        throw new ErrorException(trans('recipe.not_exist', ['id' => $request->id]), Response::HTTP_NOT_FOUND);
    }

}
