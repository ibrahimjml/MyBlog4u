<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckIfBlocked;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryInterface;
use Illuminate\View\View;
class CategoryController extends Controller
{
    public function __construct(private CategoryInterface $repo)
  {
    $this->middleware(['auth','verified',CheckIfBlocked::class]);
  }

    public function viewcategory(Category $category):View
    {

    $posts = $this->repo->getPostsByCategory($category);

      return view('categories.show', [
        'posts' => $posts,
        'currentCategory' => $category,
      ]);
    }
}
