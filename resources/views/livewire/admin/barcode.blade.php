<div class="p-6 lg:p-8">
  <div class="flex flex-col items-start justify-between gap-4 mb-6 md:flex-row md:items-center">
    <div>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        {{ __('Barcode List') }}
      </h2>
    </div>
    <div class="flex flex-col w-full gap-2 sm:flex-row sm:w-auto">
      <x-button class="justify-center w-full sm:w-auto" href="{{ route('admin.barcodes.create') }}">
        Buat Barcode Baru
      </x-button>
      <x-secondary-button class="justify-center w-full sm:w-auto">
        <a href="{{ route('admin.barcodes.downloadall') }}">Download Semua</a>
      </x-secondary-button>
    </div>
  </div>

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th scope="col" class="px-6 py-3">
            No
          </th>
          <th scope="col" class="px-6 py-3">
            Nama Barcode
          </th>
          <th scope="col" class="px-6 py-3">
            Koordinat
          </th>
          <th scope="col" class="px-6 py-3">
            Radius (meter)
          </th>
          <th scope="col" class="px-6 py-3 text-center">
            Aksi
          </th>
        </tr>
      </thead>
      <tbody>
        @forelse ($barcodes as $barcode)
          <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" style="width: 50px;">
              {{ $loop->iteration }}
            </td>
            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
              {{ $barcode->name }}
            </td>
            <td class="px-6 py-4">
               <a href="https://www.google.com/maps/search/?api=1&query={{ $barcode->latitude }},{{ $barcode->longitude }}" target="_blank" class="text-blue-600 hover:underline dark:text-blue-500">
                 {{ $barcode->latitude }}, {{ $barcode->longitude }}
               </a>
            </td>
            <td class="px-6 py-4">
              {{ $barcode->radius }} m
            </td>
            <td class="px-6 py-4 text-center">
               <x-secondary-button class="mb-1" href="{{ route('admin.barcodes.download', $barcode->id) }}" title="Download">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M12 12.75l-7.5-7.5 7.5-7.5m0 0l7.5 7.5M12 5.25v9.75" />
                  </svg>
               </x-secondary-button>
               <x-button class="mb-1 bg-yellow-500 hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700" href="{{ route('admin.barcodes.edit', $barcode->id) }}" title="Edit">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                  </svg>
               </x-button>
               <x-danger-button class="mb-1" wire:click="confirmDeletion({{ $barcode->id }}, '{{ $barcode->name }}')" title="Delete">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                  </svg>
               </x-danger-button>
            </td>
          </tr>
        @empty
          <tr>
              <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                  Tidak ada data barcode.
              </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <x-confirmation-modal wire:model="confirmingDeletion">
    <x-slot name="title">
      Hapus Barcode
    </x-slot>

    <x-slot name="content">
      Apakah Anda yakin ingin menghapus <b>{{ $deleteName }}</b>?
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button wire:click="$toggle('confirmingDeletion')" wire:loading.attr="disabled">
        {{ __('Cancel') }}
      </x-secondary-button>

      <x-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
        {{ __('Confirm') }}
      </x-danger-button>
    </x-slot>
  </x-confirmation-modal>
</div>
