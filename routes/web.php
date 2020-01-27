<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


use App\Interest;
use App\Article;




Route::put('family/parent/{item}', 'FamilyController@parent')->name('family/parent');
Route::resource('family', 'FamilyController');


Route::get('createuser', 'ParentController@createUser');
Route::get('createMother/{user_id}/{name}', 'ParentController@createMother');
Route::get('createFather/{user_id}/{name}', 'ParentController@createFather');
Route::get('user/relationships/{user_id}', 'ParentController@getUserRelationships');
Route::get('mother/child/{mother_id}', 'ParentController@childOfMother');








Route::get('showdebugstuff/', function (Request $request) {



    return view('welcome');
    return response()->view('welcome');
    return redirect()->view('welcome');
    return Illuminate\Support\Facades\View::make('welcome');

    // $articles = Article::addSelect(['interest' => Interest::select('text')->whereColumn('id', 'interest_id')->limit(1)])->whereBetween('id', [1, 20])->get();
    // ddd($articles);

    // $query = App\Father::first()->user();
    // dd($query->toSql(), $query->getBindings());
    //ddd($query->toSql(), $query->getBindings());
});


















Route::get('nochunk', function () {
    $articles = Article::all()->filter(function ($article) {
        return $article->id > 10;
    });

    $i = 0;

    foreach ($articles as $article) {
        $i++;
    }



    return 'Iterated through ' . $i . ' Objects and RAM usage is: ' . (round(memory_get_peak_usage() / 1024 / 1024)) . 'MB';
});













Route::get('chunk', function () {
    $i = 0;

    DB::listen(function ($e) {
        dump($e->sql);
    });
    // &$ bedeutet, dass eine Referenz und nicht der Wert übergeben wird.
    $articles = Article::chunk(1000, function ($articles) use (&$i) {
        $articles->filter(function ($article) use (&$i) {
            $i++;
            return $article->id > 10;
        });
    });
    return 'Iterated through ' . $i . ' Objects and RAM usage is: ' . (round(memory_get_peak_usage() / 1024 / 1024)) . 'MB';
});






















Route::get('cursor', function () {


    DB::listen(function ($e) {
        dump($e->sql);
    });

    $articles = Article::cursor()->filter(function ($article) {
        return $article->id > 10;
    });

    $i = 0;

    foreach ($articles as $article) {
        $i++;
    }

    return 'Iterated through ' . $i . ' Objects and RAM usage is: ' . (round(memory_get_peak_usage() / 1024 / 1024)) . 'MB';
});



















// Route::get('article/create/{title}/{text}/{interest_id}', function ($title, $text, $interest_id) {
//     $article = new Article;
//     $article->title = $title;
//     $article->text = $text;
//     $article->interest_id = $interest_id;
//     $article->save();
// });
// Route::get('interest/create/{text}', function ($text) {
//     $interest = new Interest;
//     $interest->text = $text;
//     $interest->save();
// });
Route::get('articles', function () {
    $articles = Article::addSelect(['interest' => Interest::select('text')->whereColumn('id', 'interest_id')->limit(1)])->first();
    return $articles->interest;
});































Route::get('/', function () {
    return view('welcome');
});

Route::get('question', function (Request $request) {

    if (!$request->filled('id')) {
        return response('Ein Fehler ist aufgetreten, da keine id übergeben wurde', Response::HTTP_NOT_FOUND);
    } elseif ($request->filled(['id', 'question']) && $request->file === "true") {
        $id = $request->id;
        $newname = $id . ".png";
        return response()->download('assets/image/Success.png', $newname);
    } elseif ($request->has('id') && !$request->filled('question')) {
        return redirect()->away('https://www.webmasters-fernakademie.de');
    } elseif ($request->filled(['id', 'question']) && $request->file !== "true") {
        return "Ihre Frage wurde erfolgreich gespeichert.";
    }
});

Route::get('helloworld', 'TestController@printMessage');


Route::get('/name/{name}/nachname/{nachname}', 'TestController@showName');

Route::get('/user/{name?}', 'TestController@showUsername')->name('username');

Route::get('names', 'CertificateController@nameList')->name('names');

Route::get('users', 'CertificateController@showUser');

Route::resource('certificates', 'CertificateController')->except([
    'index', 'edit', 'update'
])->names([
    'create' => 'certificates.certify'
]);

Route::get('interests', 'InterestController@index');
Route::get('interests/create/{id}/{text}', 'InterestController@create');
Route::get('interests/delete/{id}', 'InterestController@delete');
