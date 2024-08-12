<div
class="divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
{{-- <h1 class="text-teal-600">HHHHHH</h1> --}}
    {{-- <h1 class="text-teal-600">HHHHHH</h1> --}}
    <x-filament::pagination :paginator="$jobVacancies" style="display: flex; justify-content: flex-end; margin: 15px 15px 15px 0px"/>
<div class="relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10">
        <table class="w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
            <thead class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                <tr class="bg-gray-50 dark:bg-white/5">
                    <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6"></th>
                    @foreach ($jobVacancies as $job)
                        <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">{{ $job->title }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                @foreach ($stages as $stage)
                    <tr
                        class="divide-x divide-gray-200 dark:divide-white/5 [@media(hover:hover)]:transition [@media(hover:hover)]:duration-75">
                        <td class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">{{ $stage->getLabel() }}</td>
                        @foreach ($jobVacancies as $job)
                            <td class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                @php
                                    $collect = $userApplyJobs;
                                    $filteredJobs = $collect->filter(function ($userApplyJob) use ($job, $stage) {
                                        return $userApplyJob->job_vacancy_id == $job->id &&
                                            $userApplyJob->current_stage->value == $stage->value;
                                    });
                                @endphp
                                {{ $filteredJobs->count() }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
