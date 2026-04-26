<div class="space-y-6">
    @php($rows = $this->rows)
    @php($roles = $this->roles)

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Dashboard</p>
            <h1 class="mt-2 text-3xl font-semibold text-foreground">Usuarios</h1>
            <p class="mt-2 text-sm text-muted-foreground">
                Gerencie os acessos da plataforma com uma interface no estilo Preline.
            </p>
        </div>

        <button
            type="button"
            wire:click="create"
            class="inline-flex items-center justify-center gap-x-2 rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-hover focus:outline-hidden"
        >
            <svg class="size-4 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/>
                <path d="M12 5v14"/>
            </svg>
            Novo usuario
        </button>
    </div>

    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid gap-4 xl:grid-cols-3">
        <div class="rounded-2xl border border-layer-line bg-layer p-5 shadow-sm xl:col-span-1">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Total de usuarios</p>
                    <p class="mt-3 text-3xl font-semibold text-foreground">{{ $this->totalUsers }}</p>
                </div>

                <div class="inline-flex size-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                    <svg class="size-5 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-layer-line bg-layer p-5 shadow-sm xl:col-span-2">
            <div class="grid gap-4 md:grid-cols-[minmax(0,1fr)_180px]">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-foreground">Buscar usuario</span>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3.5">
                            <svg class="size-4 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            class="block w-full rounded-lg border border-layer-line bg-surface py-3 ps-10 pe-4 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                            placeholder="Busque por nome ou e-mail"
                        >
                    </div>
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-foreground">Por pagina</span>
                    <select
                        wire:model.live="quantity"
                        class="block w-full rounded-lg border border-layer-line bg-surface px-3 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </label>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-layer-line bg-layer shadow-sm">
        <div class="flex items-center justify-between border-b border-layer-line px-5 py-4">
            <div>
                <h2 class="text-lg font-semibold text-foreground">Lista de usuarios</h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    {{ $rows->total() }} registro(s) encontrado(s)
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-layer-line">
                <thead class="bg-surface">
                    <tr>
                        <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">
                            <button type="button" wire:click="sort('name')" class="inline-flex items-center gap-x-2">
                                Nome
                                @if ($sortBy === 'name')
                                    <span class="text-primary">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">
                            <button type="button" wire:click="sort('email')" class="inline-flex items-center gap-x-2">
                                E-mail
                                @if ($sortBy === 'email')
                                    <span class="text-primary">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">
                            Roles
                        </th>
                        <th class="px-5 py-3 text-start text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">
                            <button type="button" wire:click="sort('created_at')" class="inline-flex items-center gap-x-2">
                                Criado em
                                @if ($sortBy === 'created_at')
                                    <span class="text-primary">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-5 py-3 text-end text-xs font-semibold uppercase tracking-[0.2em] text-muted-foreground">
                            Acoes
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-layer-line">
                    @forelse ($rows as $user)
                        <tr class="bg-layer">
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="inline-flex size-10 items-center justify-center rounded-full bg-primary/10 text-sm font-semibold text-primary">
                                        {{ $user->initials() }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-foreground">{{ $user->name }}</p>
                                        <p class="text-sm text-muted-foreground">ID #{{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-foreground">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @forelse ($user->roles as $role)
                                        <span class="inline-flex rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-muted-foreground">Sem roles</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-muted-foreground">{{ $user->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        wire:click="edit({{ $user->id }})"
                                        class="inline-flex items-center gap-x-2 rounded-lg border border-layer-line bg-surface px-3 py-2 text-sm font-medium text-foreground transition hover:bg-muted-hover focus:outline-hidden"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="confirmDelete({{ $user->id }})"
                                        class="inline-flex items-center gap-x-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100 focus:outline-hidden"
                                    >
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-14 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <div class="inline-flex size-16 items-center justify-center rounded-full bg-surface text-muted-foreground">
                                        <svg class="size-7" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"/>
                                            <path d="m21 21-4.3-4.3"/>
                                        </svg>
                                    </div>
                                    <h3 class="mt-4 text-lg font-semibold text-foreground">Nenhum usuario encontrado</h3>
                                    <p class="mt-2 text-sm text-muted-foreground">
                                        Tente ajustar a busca ou crie um novo usuario.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-layer-line px-5 py-4">
            {{ $rows->links() }}
        </div>
    </div>

    @if ($showFormModal)
        <div class="fixed inset-0 z-80 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/50" wire:click="closeFormModal"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative z-10 w-full max-w-2xl rounded-2xl border border-layer-line bg-layer shadow-xl">
                    <div class="flex items-center justify-between border-b border-layer-line px-6 py-4">
                        <div>
                            <h3 class="text-lg font-semibold text-foreground">
                                {{ $editingUserId ? 'Editar usuario' : 'Novo usuario' }}
                            </h3>
                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ $editingUserId ? 'Atualize os dados do usuario selecionado.' : 'Preencha os dados para criar um novo acesso.' }}
                            </p>
                        </div>

                        <button type="button" wire:click="closeFormModal" class="inline-flex size-9 items-center justify-center rounded-full text-muted-foreground transition hover:bg-muted-hover hover:text-foreground">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="save" class="space-y-5 px-6 py-6">
                        <div class="grid gap-5 md:grid-cols-2">
                            <label class="block md:col-span-2">
                                <span class="mb-2 block text-sm font-medium text-foreground">Nome</span>
                                <input
                                    type="text"
                                    wire:model.live="name"
                                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                    placeholder="Digite o nome do usuario"
                                >
                                @error('name')
                                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="block md:col-span-2">
                                <span class="mb-2 block text-sm font-medium text-foreground">E-mail</span>
                                <input
                                    type="email"
                                    wire:model.live="email"
                                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                    placeholder="usuario@empresa.com"
                                >
                                @error('email')
                                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>

                            <div class="block md:col-span-2">
                                <div class="mb-2 flex items-center justify-between gap-3">
                                    <span class="block text-sm font-medium text-foreground">Roles</span>
                                    <span class="text-xs text-muted-foreground">{{ count($role_ids) }} selecionada(s)</span>
                                </div>

                                <div class="grid gap-3 rounded-xl border border-layer-line bg-surface p-4 md:grid-cols-2">
                                    @forelse ($roles as $role)
                                        <label class="flex items-center gap-3 rounded-lg border border-layer-line/70 px-3 py-2 text-sm text-foreground">
                                            <input
                                                type="checkbox"
                                                value="{{ $role->id }}"
                                                wire:model.live="role_ids"
                                                class="rounded border-layer-line text-primary focus:ring-primary"
                                            >
                                            <span>{{ $role->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm text-muted-foreground">Nenhuma role cadastrada ainda.</p>
                                    @endforelse
                                </div>
                                @error('role_ids.*')
                                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </div>

                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-foreground">
                                    {{ $editingUserId ? 'Nova senha' : 'Senha' }}
                                </span>
                                <input
                                    type="password"
                                    wire:model.live="password"
                                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                    placeholder="{{ $editingUserId ? 'Preencha somente se quiser alterar' : 'Minimo de 8 caracteres' }}"
                                >
                                @error('password')
                                    <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="block">
                                <span class="mb-2 block text-sm font-medium text-foreground">Confirmar senha</span>
                                <input
                                    type="password"
                                    wire:model.live="password_confirmation"
                                    class="block w-full rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm text-foreground focus:border-primary-focus focus:outline-hidden focus:ring-0"
                                    placeholder="Repita a senha"
                                >
                            </label>
                        </div>

                        <div class="flex flex-col-reverse gap-3 border-t border-layer-line pt-5 sm:flex-row sm:justify-end">
                            <button
                                type="button"
                                wire:click="closeFormModal"
                                class="inline-flex items-center justify-center rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm font-semibold text-foreground transition hover:bg-muted-hover"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white transition hover:bg-primary-hover"
                            >
                                {{ $editingUserId ? 'Salvar alteracoes' : 'Criar usuario' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if ($showDeleteModal)
        <div class="fixed inset-0 z-80 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/50" wire:click="closeDeleteModal"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative z-10 w-full max-w-lg rounded-2xl border border-layer-line bg-layer shadow-xl">
                    <div class="border-b border-layer-line px-6 py-4">
                        <h3 class="text-lg font-semibold text-foreground">Excluir usuario</h3>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Esta acao nao pode ser desfeita.
                        </p>
                    </div>

                    <div class="px-6 py-6">
                        <p class="text-sm leading-6 text-foreground">
                            Tem certeza que deseja excluir o usuario
                            <span class="font-semibold">{{ $deletingUserName }}</span>?
                        </p>

                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <button
                                type="button"
                                wire:click="closeDeleteModal"
                                class="inline-flex items-center justify-center rounded-lg border border-layer-line bg-surface px-4 py-3 text-sm font-semibold text-foreground transition hover:bg-muted-hover"
                            >
                                Cancelar
                            </button>
                            <button
                                type="button"
                                wire:click="destroy"
                                class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700"
                            >
                                Confirmar exclusao
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
