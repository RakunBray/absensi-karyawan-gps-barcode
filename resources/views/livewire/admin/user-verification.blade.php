<div>
  <div class="mb-4 flex-col items-center gap-5 sm:flex-row md:flex md:justify-between lg:mr-4">
    <h3 class="mb-4 text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200 md:mb-0">
      Verifikasi Akun Karyawan
    </h3>
  </div>

  <div class="space-y-8">
    <!-- Pending Users -->
    <div>
      <div class="mb-4 flex items-center justify-between">
        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300">
          Menunggu Persetujuan
          <span class="ml-2 rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
            {{ $pendingUsers->count() }}
          </span>
        </h4>
      </div>
      
      <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm dark:border-gray-700">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-900">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Nama / NIP</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Kontak</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Status</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
            @forelse($pendingUsers as $user)
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">NIP: {{ $user->nip ?? '-' }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white">{{ $user->email }}</div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->phone }}</div>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">
                    Pending
                  </span>
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                  <div class="flex justify-end gap-2">
                    <x-button wire:click="approveUser('{{ $user->id }}')" class="bg-green-600 hover:bg-green-700">
                      Setujui
                    </x-button>
                    <x-danger-button wire:click="rejectUser('{{ $user->id }}')">
                      Tolak
                    </x-danger-button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                  Tidak ada akun menunggu persetujuan
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Approved Users -->
    <div>
      <div class="mb-4 flex items-center justify-between">
        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300">
          Disetujui
          <span class="ml-2 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
            {{ $approvedUsers->count() }}
          </span>
        </h4>
      </div>

      <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm dark:border-gray-700">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-900">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Nama / NIP</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Kontak</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Status Email</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
            @forelse($approvedUsers as $user)
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">NIP: {{ $user->nip ?? '-' }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white">{{ $user->email }}</div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->phone }}</div>
                </td>
                <td class="px-6 py-4">
                  @if($user->email_verified_at)
                    <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                      Terverifikasi
                    </span>
                  @else
                    <span class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                      Belum Verifikasi
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                  <div class="flex justify-end gap-2">
                    @if(!$user->email_verified_at)
                      <x-button wire:click="verifyUser('{{ $user->id }}')" class="bg-blue-600 hover:bg-blue-700 text-xs">
                        Verif Email
                      </x-button>
                    @endif
                    <x-secondary-button wire:click="deactivateUser('{{ $user->id }}')">
                      Nonaktifkan
                    </x-secondary-button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                  Tidak ada akun disetujui
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Rejected Users -->
    <div>
      <div class="mb-4 flex items-center justify-between">
        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300">
          Ditolak
          <span class="ml-2 rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
            {{ $rejectedUsers->count() }}
          </span>
        </h4>
      </div>

      <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm dark:border-gray-700">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-900">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Nama / NIP</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Kontak</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Status</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
            @forelse($rejectedUsers as $user)
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">NIP: {{ $user->nip ?? '-' }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white">{{ $user->email }}</div>
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->phone }}</div>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                    Ditolak
                  </span>
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                  <div class="flex justify-end gap-2">
                    <x-button wire:click="approveUser('{{ $user->id }}')" class="bg-green-600 hover:bg-green-700">
                      Setujui Kembali
                    </x-button>
                    <x-danger-button wire:click="deleteUser('{{ $user->id }}')">
                      Hapus
                    </x-danger-button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                  Tidak ada akun ditolak
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>