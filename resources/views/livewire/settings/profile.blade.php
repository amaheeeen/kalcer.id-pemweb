public function updateProfileInformation(): void
{
    /** @var \App\Models\User $user */ // <--- TAMBAHKAN BARIS INI
    $user = Auth::user();

    $validated = $this->validate();

    $user->fill($validated); // Sekarang VS Code tahu 'fill' itu ada

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    // ... code selanjutnya
}