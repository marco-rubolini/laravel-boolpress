<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;
use App\Category;
use App\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('category', 'tags')->get();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $data = [
            'categories' => $categories,
            'tags' => $tags
        ];
        return view('admin.posts.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Validazione dei dati
        $request->validate([
            'title' => 'required|max:255|unique:posts,title',
            'content' => 'required',
            'image' => 'image|max:1024'
        ]);
        $dati = $request->all();
        // Genero lo slug a partire dal titolo
        $slug = Str::of($dati['title'])->slug('-');
        $slug_originale = $slug;
        $post_trovato = Post::where('slug', $slug)->first();
        $contatore = 0;
        while ($post_trovato) {
            $contatore++;
            // Genero un nuovo slug concatenando un contatore
            $slug = $slug_originale . '-' . $contatore;
            $post_trovato = Post::where('slug', $slug)->first();

        }
        // Arrivati a questo punto sono sicuro che lo slug sia unico
        $dati['slug'] = $slug;

        // Verifico se l'utente ha caricato una foto
        if ($dati['image']) {
            // carico l'immagine
            $img_path = Storage::put('uploads', $dati['image']);
            $dati['cover_image'] = $img_path;
        }

        // Salvo i dati del post
        $nuovo_post = new Post();
        $nuovo_post -> fill($dati);
        $nuovo_post->save();
        if (!empty($dati['tags'])) {
            $nuovo_post->tags()->sync($dati['tags']);
        }


        return redirect()->route('admin.posts.index');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        if ($post) {
            return view('admin.posts.show', compact('post'));
        } else {
            return abort('404');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        if ($post) {
            $categories = Category::all();
            $tags = Tag::all();
            $data = [
                'post' => $post,
                'categories' => $categories,
                'tags' => $tags
            ];
            return view('admin.posts.edit', $data);
    } else {
        return abort('404');
    }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255|unique:posts,title,'.$id,
            'content' => 'required',
            'image' => 'image|max:1024'
        ]);
        $dati = $request->all();
        // Genero lo slug a partire dal titolo
        $slug = Str::of($dati['title'])->slug('-');
        $slug_originale = $slug;
        $post_trovato = Post::where('slug', $slug)->first();
        $contatore = 0;
        while ($post_trovato) {
            $contatore++;
            // Genero un nuovo slug concatenando un contatore
            $slug = $slug_originale . '-' . $contatore;
            $post_trovato = Post::where('slug', $slug)->first();

        }
        // Arrivati a questo punto sono sicuro che lo slug sia unico
        $dati['slug'] = $slug;

        // Verifico se l'utente ha caricato una foto
        if ($dati['image']) {
            // carico l'immagine
            $img_path = Storage::put('uploads', $dati['image']);
            $dati['cover_image'] = $img_path;
        }

        $post = Post::find($id);
        $post -> update($dati);
        // Se l'utente ha selezionato dei tag li associo al post
        if (!empty($dati['tags'])) {
            $post->tags()->sync($dati['tags']);
        } else {
            // L'utente non ha selezionato nessun tag o deselezionato quelli che c'erano
            // $post->tags()->detach();
            $post->tags()->sync([]);
        }

        return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if ($post) {
            $post->delete();
            return redirect()->route('admin.posts.index');
        } else {
            return abort('404');
        }
    }
}
