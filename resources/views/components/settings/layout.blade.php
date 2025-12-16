<div class="flex flex-col md:flex-row gap-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <aside class="w-full md:w-64 shrink-0">
        <nav class="flex flex-row md:flex-col gap-1 overflow-x-auto pb-4 md:pb-0">
            
            <a href="{{ route('profile.edit') }}" wire:navigate 
               class="px-4 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap
               {{ request()->routeIs('profile.edit') 
                  ? 'bg-purple-50 text-purple-700 font-bold' 
                  : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
               👤 Profile
            </a>

            <a href="{{ route('user-password.edit') }}" wire:navigate 
               class="px-4 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap
               {{ request()->routeIs('user-password.edit') 
                  ? 'bg-purple-50 text-purple-700 font-bold' 
                  : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
               🔒 Password
            </a>

            <a href="{{ route('appearance.edit') }}" wire:navigate 
               class="px-4 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap
               {{ request()->routeIs('appearance.edit') 
                  ? 'bg-purple-50 text-purple-700 font-bold' 
                  : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
               🎨 Appearance
            </a>
            
        </nav>
    </aside>

    <div class="flex-1 min-w-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $heading ?? 'Settings' }}</h2>
            <p class="text-gray-500 text-sm mb-6">{{ $subheading ?? 'Kelola pengaturan akun Anda.' }}</p>
            
            {{ $slot }}
        </div>
    </div>
</div>