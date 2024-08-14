<?php

namespace App\Http\Controllers\Wine;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Http\RedirectResponse;


class CategoryController extends Controller
{
    public function __construct(private readonly CategoryRepositoryInterface $repository)
    {}


    public function index(): View
    {

        return view('wine.category.index', [
            'categories' => $this->repository->paginate(
                counts: ['wines'],
            )
        ]);
    }

    public function create (): View
    {
        return view('wine.category.create', [
            'category' => $this->repository->model(),
            'action' => route('categories.store'),
            'method' => 'POST',
            'submit'=> 'Crear',
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $this->repository->create($request->validated());

        session()->flash('success', 'Categoria creada con èxito');

        return redirect()->route('categories.index');
    }


    public function edit(Category $category): View
    {
        return view('wine.category.edit', [
            'category' => $category,
            'action' => route('categories.update', $category),
            'method' => 'PUT',
            'submit'=> '    Actualizar',
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $this->repository->update($request->validated(), $category);

        session()->flash('success', 'Categoria creada con èxito');

        return redirect()->route('categories.index');
    }

    public function destroy(Category $category): RedirectResponse
    {
        try{
            $this->repository->delete($category);
            session()->flash('succes','Categoria eliminada con exito');
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }

        return redirect()->route('categories.index');
    }
}
