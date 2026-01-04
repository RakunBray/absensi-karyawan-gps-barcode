<div>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Verifikasi Akun Karyawan</h2>

        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Pending Users -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4">Akun Menunggu Persetujuan</h3>
            @if($pendingUsers->count() > 0)
                <div class="space-y-4">
                    @foreach($pendingUsers as $user)
                        <div class="border rounded-lg p-4 bg-yellow-50">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-bold">{{ $user->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $user->email }} | {{ $user->phone }}</p>
                                    <p class="text-sm text-gray-500">NIP: {{ $user->nip }}</p>
                                </div>
                                <div class="space-x-2">
                                    <button wire:click="approveUser({{ $user->id }})" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                        Setujui
                                    </button>
                                    <button wire:click="rejectUser({{ $user->id }})" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                        Tolak
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Tidak ada akun menunggu persetujuan.</p>
            @endif
        </div>

        <!-- Approved Users -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4">Akun Disetujui</h3>
            @if($approvedUsers->count() > 0)
                <div class="space-y-4">
                    @foreach($approvedUsers as $user)
                        <div class="border rounded-lg p-4 bg-green-50">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-bold">{{ $user->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $user->email }} | {{ $user->phone }}</p>
                                    <p class="text-sm text-gray-500">NIP: {{ $user->nip }}</p>
                                    <p class="text-sm {{ $user->email_verified_at ? 'text-green-600' : 'text-red-600' }}">
                                        Email: {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                                    </p>
                                </div>
                                <div class="space-x-2">
                                    @if(!$user->email_verified_at)
                                        <button wire:click="verifyUser({{ $user->id }})" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                            Verifikasi Email
                                        </button>
                                    @endif
                                    <button wire:click="deactivateUser({{ $user->id }})" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                                        Nonaktifkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Tidak ada akun disetujui.</p>
            @endif
        </div>

        <!-- Rejected Users -->
        <div>
            <h3 class="text-xl font-semibold mb-4">Akun Ditolak</h3>
            @if($rejectedUsers->count() > 0)
                <div class="space-y-4">
                    @foreach($rejectedUsers as $user)
                        <div class="border rounded-lg p-4 bg-red-50">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-bold">{{ $user->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $user->email }} | {{ $user->phone }}</p>
                                    <p class="text-sm text-gray-500">NIP: {{ $user->nip }}</p>
                                </div>
                                <div class="space-x-2">
                                    <button wire:click="approveUser({{ $user->id }})" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                        Setujui
                                    </button>
                                    <button wire:click="deleteUser({{ $user->id }})" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Tidak ada akun ditolak.</p>
            @endif
        </div>
    </div>
</div>