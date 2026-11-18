<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckIfBlocked;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
class CategoryController extends Controller
{
  public function __construct(private CategoryInterface $repo)
  {
    $this->middleware(CheckIfBlocked::class);
  }
  public function __invoke(Category $category, Request $request): View|JsonResponse
  {
    $perPage = $request->get('perpage', 10);
    $page = $request->get('categories_page', 1);
    $sorts = $request->get('sort', 'latest');

    guest_not_allowed_filter_following($sorts);

    $category->loadCount('posts');
    $posts = $this->repo->getPostsByCategory($category, $sorts, $perPage, $page);
    attach_author_cards($posts);
    $sliders = $this->repo->getSliderImages($category);

    if ($request->ajax()) {

      $html = view('categories.categories-ajax', ['posts' => $posts, 'currentCategory' => $category])->render();
      return infinite_scroll_response($html,$posts);
    }

    return view('categories.show', [
      'posts' => $posts,
      'currentCategory' => $category,
      'sliders' => $sliders,
      'sorts' => $sorts
    ]);
  }
}