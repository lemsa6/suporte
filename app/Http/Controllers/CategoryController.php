<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Lista de categorias
     */
    public function index(): View
    {
        $categories = $this->getCategoriesWithStats();
        $stats = $this->getCategoryStats();
        
        return view('categories.index', compact('categories', 'stats'));
    }

    /**
     * Obtém categorias com estatísticas
     */
    private function getCategoriesWithStats()
    {
        return Category::withCount('tickets')
            ->withCount(['tickets as active_tickets_count' => function ($q) {
                $q->whereIn('status', ['aberto', 'em_andamento']);
            }])
            ->orderBy('name')
            ->paginate(15);
    }

    /**
     * Obtém estatísticas das categorias
     */
    private function getCategoryStats(): array
    {
        return [
            'total' => Category::count(),
            'active' => Category::where('is_active', true)->count(),
            'inactive' => Category::where('is_active', false)->count(),
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('status', 'aberto')->count(),
        ];
    }

    /**
     * Formulário de criação
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Armazena nova categoria
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'color' => 'required|string|max:7|regex:/^#[0-9A-F]{6}$/i',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);
        
        Category::create($validated);
        
        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    /**
     * Formulário de edição
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Atualiza categoria
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'color' => 'required|string|max:7|regex:/^#[0-9A-F]{6}$/i',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);
        
        $category->update($validated);
        
        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove categoria
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Verifica se há tickets usando esta categoria
        if ($category->tickets()->exists()) {
            return back()->with('error', 'Não é possível excluir categoria com tickets associados!');
        }
        
        $category->delete();
        
        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoria removida com sucesso!');
    }

    /**
     * Toggle status ativo/inativo
     */
    public function toggleStatus(Category $category): RedirectResponse
    {
        $category->update(['is_active' => !$category->is_active]);
        
        $status = $category->is_active ? 'ativada' : 'desativada';
        
        return back()->with('success', "Categoria {$status} com sucesso!");
    }

    /**
     * API: Lista categorias para select
     */
    public function apiList(): JsonResponse
    {
        $categories = Category::active()
            ->select('id', 'name', 'color')
            ->orderBy('name')
            ->get();
        
        return response()->json($categories);
    }

    /**
     * API: Estatísticas da categoria
     */
    public function apiStats(Category $category): JsonResponse
    {
        $stats = [
            'total_tickets' => $category->tickets()->count(),
            'open_tickets' => $category->tickets()->open()->count(),
            'in_progress_tickets' => $category->tickets()->inProgress()->count(),
            'resolved_tickets' => $category->tickets()->resolved()->count(),
            'closed_tickets' => $category->tickets()->closed()->count(),
            'active_tickets' => $category->tickets()->active()->count(),
        ];
        
        return response()->json($stats);
    }
}
