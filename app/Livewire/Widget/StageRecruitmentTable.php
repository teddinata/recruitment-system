<?php

namespace App\Livewire\Widget;

use Livewire\Component;
use App\Models\JobVacancy;
use App\Models\UserApplyJob;
use App\Enums\StageRecruitment;
use Livewire\WithPagination;

class StageRecruitmentTable extends Component
{
    use WithPagination;
    
    public $perPage = 3;
    
    public $stage;

    public function mount()
    {
        $this->stage = StageRecruitment::cases();
    }

    public function render()
    {
        $jobVacancies = JobVacancy::query()->paginate($this->perPage);
        $userApplyJob = UserApplyJob::all();

        return view('livewire.widget.stage-recruitment-table', [
            'stages' => $this->stage,
            'jobVacancies' => $jobVacancies,
            'userApplyJobs' => $userApplyJob,
        ]);
    }
}
