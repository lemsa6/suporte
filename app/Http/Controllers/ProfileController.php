<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // Middleware aplicado nas rotas

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit(): View
    {
        $user = auth()->user();
        
        // Lista de fusos horários comuns
        $timezones = [
            'America/Sao_Paulo' => 'Brasília (GMT-3)',
            'America/New_York' => 'Nova York (GMT-5)',
            'America/Los_Angeles' => 'Los Angeles (GMT-8)',
            'Europe/London' => 'Londres (GMT+0)',
            'Europe/Paris' => 'Paris (GMT+1)',
            'Asia/Tokyo' => 'Tóquio (GMT+9)',
            'Australia/Sydney' => 'Sydney (GMT+10)',
            'UTC' => 'UTC (GMT+0)',
        ];
        
        return view('profile.edit', compact('user', 'timezones'));
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Senha alterada com sucesso!');
    }

    /**
     * Show the user's profile information.
     *
     * @return \Illuminate\View\View
     */
    public function show(): View
    {
        $user = auth()->user();
        
        // Carregar dados relacionados baseado no perfil
        if ($user->isCliente()) {
            $user->load(['assignedTickets' => function ($query) {
                $query->latest()->limit(10);
            }]);
        } elseif ($user->isTecnico()) {
            $user->load(['assignedTickets' => function ($query) {
                $query->latest()->limit(10);
            }]);
        }
        
        return view('profile.show', compact('user'));
    }

    /**
     * Show the user's activity log.
     *
     * @return \Illuminate\View\View
     */
    public function activity(): View
    {
        $user = auth()->user();
        
        // Carregar atividades recentes
        $recentActivity = collect();
        
        if ($user->isCliente()) {
            // Atividade de tickets do cliente
            $recentActivity = $user->assignedTickets()
                ->with(['client', 'category'])
                ->latest()
                ->limit(20)
                ->get();
        } elseif ($user->isTecnico()) {
            // Atividade de tickets atribuídos ao técnico
            $recentActivity = $user->assignedTickets()
                ->with(['client', 'category', 'contact'])
                ->latest()
                ->limit(20)
                ->get();
        } elseif ($user->isAdmin()) {
            // Atividade geral para admin
            $recentActivity = \App\Models\Ticket::with(['client', 'category', 'contact'])
                ->latest()
                ->limit(20)
                ->get();
        }
        
        return view('profile.activity', compact('user', 'recentActivity'));
    }

    /**
     * Show the user's preferences.
     *
     * @return \Illuminate\View\View
     */
    public function preferences(): View
    {
        $user = auth()->user();
        
        return view('profile.preferences', compact('user'));
    }

    /**
     * Update the user's preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePreferences(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'notifications_email' => ['boolean'],
            'notifications_sms' => ['boolean'],
            'language' => ['string', 'in:pt_BR,en'],
            'timezone' => ['string', 'timezone'],
        ]);

        // Atualizar preferências (implementar conforme necessário)
        // $user->preferences()->updateOrCreate([], $validated);

        return back()->with('success', 'Preferências atualizadas com sucesso!');
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'delete_confirmation' => ['required', 'string', 'in:DELETE'],
        ]);

        // Fazer logout antes de deletar
        auth()->logout();
        
        // Deletar a conta
        $user->delete();
        
        // Invalidar sessão
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sua conta foi excluída com sucesso.');
    }
}
