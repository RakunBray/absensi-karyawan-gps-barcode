<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class EmployeeComponent extends Component
{
    use WithPagination, InteractsWithBanner, WithFileUploads;

    public UserForm $form;

    public $deleteName = null;
    public $creating = false;
    public $editing = false;
    public $confirmingDeletion = false;
    public $selectedId = null;
    public $showDetail = null;

    # filter
    public ?string $division = null;
    public ?string $jobTitle = null;
    public ?string $education = null;
    public ?string $search = null;

    /* ======================================================
     |  DETAIL
     ======================================================*/
    public function show($id)
    {
        $this->form->setUser(User::findOrFail($id));
        $this->showDetail = true;
    }

    /* ======================================================
     |  CREATE
     ======================================================*/
    public function showCreating()
    {
        $this->form->resetErrorBag();
        $this->form->reset();
        $this->creating = true;
        $this->form->password = 'password';
    }

    public function create()
    {
        $this->form->store();

        $this->creating = false;
        $this->banner('Karyawan berhasil ditambahkan (belum diverifikasi).');
    }

    /* ======================================================
     |  EDIT
     ======================================================*/
    public function edit($id)
    {
        $this->form->resetErrorBag();
        $this->form->reset();

        $this->editing = true;
        $this->form->setUser(User::findOrFail($id));
    }

    public function update()
    {
        $this->form->update();

        $this->editing = false;
        $this->banner('Data karyawan berhasil diperbarui.');
    }

    /* ======================================================
     |  VERIFIKASI AKUN
     ======================================================*/
    public function verify(string $id): void
    {
        $user = User::where('group', 'user')->findOrFail($id);

        if ($user->email_verified_at && $user->status === 'approved') {
            return;
        }

        $user->update([
            'email_verified_at' => now(),
            'status' => 'approved',
        ]);

        $user->notify(new \App\Notifications\AccountApproved());

        $this->banner('Akun karyawan berhasil diverifikasi.');
    }

    public function deactivate(string $id): void
    {
        $user = User::where('group', 'user')->findOrFail($id);

        $user->update([
            'email_verified_at' => null,
            'status' => 'pending',
        ]);

        $this->banner('Akun karyawan berhasil dinonaktifkan.');
    }

    /* ======================================================
     |  DELETE
     ======================================================*/
    public function confirmDeletion($id, $name)
    {
        $this->deleteName = $name;
        $this->selectedId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        $user = User::findOrFail($this->selectedId);

        $this->form->setUser($user)->delete();

        $this->confirmingDeletion = false;
        $this->banner('Akun karyawan berhasil dihapus.');
    }

    public function deleteProfilePhoto()
    {
        $this->form->deleteProfilePhoto();
    }

    /* ======================================================
     |  RENDER
     ======================================================*/
    public function render()
    {
        $users = User::where('group', 'user')
            ->when($this->search, function (Builder $q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nip', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->division, fn (Builder $q) => $q->where('division_id', $this->division))
            ->when($this->jobTitle, fn (Builder $q) => $q->where('job_title_id', $this->jobTitle))
            ->when($this->education, fn (Builder $q) => $q->where('education_id', $this->education))
            ->orderBy('name')
            ->paginate(20);

        return view('livewire.admin.employees', [
            'users' => $users,
        ]);
    }
}
