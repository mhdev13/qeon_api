<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        //get data from table article
        $article = Article::latest()->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data article',
            'data'    => $article  
        ], 200);
    }

    public function show($id)
    {
        //find article by ID
        $article = Article::findOrfail($id);

        //make response JSON
        return response()->json([
           'success' => true,
           'message' => 'Detail Data article',
           'data'    => $article 
        ], 200);
    }

    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'body' => 'required',
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //save to database
        $article = Article::create([
            'title'     => $request->title,
            'body'   => $request->body
        ]);

        //success save to database
        if($article) {

            return response()->json([
                'success' => true,
                'message' => 'article Created',
                'data'    => $article  
            ], 201);

        } 

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'article Failed to Save',
        ], 409);

    }

    public function update(Request $request, Article $article)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'body' => 'required',
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //find article by ID
        $article = Article::findOrFail($article->id);

        if($article) {

            //update article
            $article->update([
                'title'     => $request->title,
                'body'   => $request->body
            ]);

            return response()->json([
                'success' => true,
                'message' => 'article Updated',
                'data'    => $article  
            ], 200);

        }

        //data article not found
        return response()->json([
            'success' => false,
            'message' => 'article Not Found',
        ], 404);
    }

    public function destroy($id)
    {
        //find article by ID
        $article = Article::findOrfail($id);

        if($article) {

            //delete article
            $article->delete();

            return response()->json([
                'success' => true,
                'message' => 'article Deleted',
            ], 200);

        }

        //data article not found
        return response()->json([
            'success' => false,
            'message' => 'article Not Found',
        ], 404);
    }
}
