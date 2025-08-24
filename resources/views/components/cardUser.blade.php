@props(['user'])
<div style="
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding: 1.5rem;
    background-color: rgba(31, 41, 55, 0.5);
    border-radius: 0.5rem;
    box-shadow: 0 25px 50px -12px rgba(54, 58, 65, 0.2);
">
    <p style="font-size:1.45rem;color:white;">{{ $user['id'] ?? '-' }}</p>
    <p style="font-weight:600;font-size:1.25rem;color:#FF2D20;" id="user-name-{{ $user['id'] }}">
        {{ $user['name'] ?? 'Sem nome' }}</p>
    <input type="text" value="{{ $user['name'] ?? '' }}" id="edit-name-{{ $user['id'] }}"
        style="display:none;font-weight:600;font-size:1.25rem;color:#FF2D20;background:rgba(255,255,255,0.1);border-radius:0.25rem;border:none;padding:0.25rem 0.5rem;" />
    <p style="font-size:0.875rem;color:#ccc;" id="user-email-{{ $user['id'] }}">{{ $user['email'] ?? 'Sem email' }}</p>
    <input type="text" value="{{ $user['email'] ?? '' }}" id="edit-email-{{ $user['id'] }}"
        style="display:none;font-size:0.875rem;color:#ccc;background:rgba(255,255,255,0.1);border-radius:0.25rem;border:none;padding:0.25rem 0.5rem;" />
    <p style="font-size:0.875rem;color:#ccc;">
        Verificado em:
        {{ $user['email_verified_at'] ? \Carbon\Carbon::parse($user['email_verified_at'])->format('d/m/Y H:i') : '-' }}
    </p>
    <p style="font-size:0.875rem;color:#ccc;">
        Criado:
        {{ $user['created_at'] ? \Carbon\Carbon::parse($user['created_at'])->format('d/m/Y H:i') : '-' }}
    </p>
    <p style="font-size:0.875rem;color:#ccc;">
        Atualizado:
        {{ $user['updated_at'] ? \Carbon\Carbon::parse($user['updated_at'])->format('d/m/Y H:i') : '-' }}
    </p>
    <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
        <!-- Edit Button -->
        <button title="Editar usuário" style="background: none; border: none; cursor: pointer;"
            onclick="window.startEditUser && window.startEditUser({{ $user['id'] }})">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                stroke="#FF2D20" style="vertical-align: middle;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.232 5.232l3.536 3.536M9 13l6.232-6.232a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13zm-6 6v-3a2 2 0 012-2h3" />
            </svg>
        </button>
        <!-- Save Button (hidden by default) -->
        <button title="Salvar" id="save-edit-{{ $user['id'] }}"
            style="display:none;background: none; border: none; cursor: pointer;"
            onclick="window.saveEditUser && window.saveEditUser({{ $user['id'] }})">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                stroke="#22c55e" style="vertical-align: middle;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </button>
        <!-- Cancel Button (hidden by default) -->
        <button title="Cancelar" id="cancel-edit-{{ $user['id'] }}"
            style="display:none;background: none; border: none; cursor: pointer;"
            onclick="window.cancelEditUser && window.cancelEditUser({{ $user['id'] }})">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                stroke="#ef4444" style="vertical-align: middle;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <!-- Delete Button (sempre lixeira, separado) -->
        <button title="Excluir usuário" style="background: none; border: none; cursor: pointer;"
            onclick="window.deleteUser && window.deleteUser({{ $user['id'] }})">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                stroke="#FF2D20" style="vertical-align: middle;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m2 0v14a2 2 0 01-2 2H8a2 2 0 01-2-2V6h12z" />
            </svg>
        </button>
    </div>
</div>

</div>
