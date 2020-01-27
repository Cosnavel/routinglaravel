<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\DB;

class InterestController extends Controller
{
    //
    public function index(Request $request)
    {


        //return response()->download('assets/image/Success.png', '1234.png');

        // return response('Hello World', Response::HTTP_F);

        // //Aufgabe 1
        // $posts = DB::table('posts');

        // //Aufgabe 2
        // $count_posts = $posts->count();
        // var_dump($count_posts);
        // //Aufgabe 3
        // DB::insert('insert into posts (title, text) value (?,?)', ['uebungsaufgabe', 'das ist schoen']);
        // //Aufgabe 4
        // $update = $posts->whereBetween('id', [6, 10])->whereNull('interest_id')->update(['text' => 'neuer Text']);
        // var_dump($update);
        // //Aufgabe 5
        // $created = $posts->whereId(1)->value('created_at');
        // var_dump($created);
        // //Aufgabe 6
        // $order_posts = $posts->whereNotNull(['text', 'interest_id'])->orderBy('id', 'desc')->get();
        // var_dump($order_posts);
        // //Aufgabe 7
        // $deleted = $posts->whereNull('text')->orWhereNull('interest_id')->delete();
        // var_dump($deleted);
    }
    public function create($id, $text)
    {
        DB::insert('insert into interests (id, text) values (:id, :text)', ['id' => $id, 'text' => $text])->toSql();
    }

    public function delete($id)
    {
        $removed = DB::delete('delete from interests where id = ?', [$id]);
        return $removed;
    }
}
