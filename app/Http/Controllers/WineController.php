<?php

namespace App\Http\Controllers;

use App\Http\Requests\WineRequest;
use App\Models\Wine;
use App\Repositories\Wine\WineRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class WineController extends Controller
{
    public function __construct(private readonly WineRepositoryInterface $repository)

    {}

    public function index(): View
    {
        return view ('wine.index', [
            'wines' => $this-> repository->paginate(
                relationships: ['category'],
            ),
        ]);
    }


    public function create(): View
    {
        return view('wine.create',[
            'wine' => $this->repository->model(),
            'action' => route('wines.store'),
            'method' => 'POST',
            'submit' => 'Crear',

        ]);
    }

    public function store(WineRequest $request): RedirectResponse
    {
        $this->repository->create($request->validated());

        session()->flash('succes', 'vino creado con exito');

        return redirect()->route('wines.index');
    }

    public function edit(Wine $wine): View
    {
        return view('wine.edit', [
            'wine' => $wine,
            'action' => route('wines.update', $wine),
            'method' => 'PUT',
            'submit'=> 'Actualizar',
        ]);
    }

    public function update(WineRequest $request, Wine $wine): RedirectResponse
    {
        $this->repository->update($request->validated(), $wine);

        session()->flash('success', 'Vino creado con èxito');

        return redirect()->route('wines.index');
    }

    public function destroy(Wine $wine): RedirectResponse
    {
        try{
            $this->repository->delete($wine);
            session()->flash('succes','Vino eliminado con exito');
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }

        return redirect()->route('wines.index');
    }
}
