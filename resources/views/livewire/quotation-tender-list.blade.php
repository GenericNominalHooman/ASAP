<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Apply Quotations') }}
    </h2>
</x-slot>

<div class="py-12 relative">
    <!-- Full Screen Loading Overlay for Manual Scrape -->
    <div wire:loading wire:target="scrape" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-gray-900 bg-opacity-80 backdrop-blur-sm text-center">
        <svg class="w-16 h-16 text-blue-500 animate-spin mx-auto mt-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <h2 class="mt-4 text-2xl font-semibold text-white px-4">Scraping in progress...</h2>
        <p class="mt-2 text-md text-gray-300 px-4">This may take a few minutes. Please do not refresh or close this page.</p>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-xl font-bold">Auto apply quotation status:</h2>
                <!-- From Uiverse.io by Bastiennnn --> 
                <div class="flex flex-col lg:flex-row flex-wrap justify-center lg:justify-between items-start gap-6 my-6 w-full">
                    @forelse($userTimers as $timer)
                    <div class="card-quotation-status" x-data="{ showLogs: false }">
                        <div class="header">
                            <div class="top justify-between">
                                <div class="flex items-center">
                                    <div class="circle">
                                        <span class="red circle2" style="{{ ($timer['status'] ?? 'pending') === 'unsuccessful' ? '' : 'opacity: 0.3; filter: grayscale(1);' }}"></span>
                                    </div>
                                    <div class="circle">
                                        <span class="yellow circle2 {{ ($timer['status'] ?? 'pending') === 'running' ? 'animate-pulse' : '' }}" style="{{ in_array($timer['status'] ?? 'pending', ['pending', 'running']) ? '' : 'opacity: 0.3; filter: grayscale(1);' }}"></span>
                                    </div>
                                    <div class="circle">
                                        <span class="green circle2" style="{{ ($timer['status'] ?? 'pending') === 'successful' ? '' : 'opacity: 0.3; filter: grayscale(1);' }}"></span>
                                    </div>
                                    <div class="title max-w-[200px]">
                                        <p id="title2" class="truncate">{{ ucfirst($timer['status'] ?? 'pending') }} | {{ \Carbon\Carbon::parse($timer['timing'])->format('H:i') }}</p>
                                    </div>
                                </div>
                                <button @click="showLogs = !showLogs" class="text-slate-400 hover:text-white transition-colors p-1" title="Toggle Logs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-200" :class="showLogs ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="code-container" x-show="showLogs" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
                            <textarea class="area" id="code" name="code" readonly="">{{ $timer['log'] ?? 'No logs available yet.' }}</textarea>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 italic mt-4 w-full text-center">No active timers found.</p>
                    @endforelse
                </div>


                <hr class="mt-4 mb-4">
                <button wire:click="scrape"
                    class="w-full py-4 mb-6 text-xl font-bold text-white transition-all duration-200 bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none">
                    Apply S3pk Quotations
                </button>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 italic">* Currently the system will only auto apply JKR, PDT and JPS quotations. More will be added in the future</p>

                <h2 class="text-xl font-bold">Quotations Applied Today:</h2>
                <div class="p-2">
                    <div
                        class="relative overflow-x-auto shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div class="w-full pb-4">
                            <label for="table-search" class="sr-only">Search</label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="table-search" wire:model.live.debounce.300ms="searchToday"
                                    class="w-full block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Search by Quotation Name, Quotation Title, Organization or Owner">
                            </div>
                        </div>
                        <table
                            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border-collapse border border-gray-200 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            <input id="checkbox-all-search" type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        No
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Organization
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Quotation Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Quotation Title
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Specialization(s)
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Begin Registeration Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        End Registeration Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Closing Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Slip
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Require Site Visit?
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Location(Site Visit)
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Date & Time(Site Visit)
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Serial Number(From Email)
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Owner
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $row_count = 1;
                                @endphp
                                @foreach ($QuotationApplicationModelToday as $QuotationApplication)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="w-4 p-4">
                                            <div class="flex items-center">
                                                <input id="checkbox-table-search-1" type="checkbox"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                            </div>
                                        </td>
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ ($QuotationApplicationModelToday->firstItem() + $loop->index) }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{$QuotationApplication->organization}}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($QuotationApplication->advert_path)
                                                <form action="{{ route('download.advert', $QuotationApplication->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                        {{ $QuotationApplication->file_name }}
                                                    </button>
                                                </form>
                                            @else
                                                {{ $QuotationApplication->file_name }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$QuotationApplication->title}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$QuotationApplication->specializations}}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($QuotationApplication->begin_register_date)
                                                {{ $QuotationApplication->begin_register_date }}<br>
                                                @php
                                                    $days = \Carbon\Carbon::parse($QuotationApplication->begin_register_date)->diffInDays(now()) * -1;
                                                @endphp
                                                <span
                                                    class="{{ $days < 0 ? 'text-black' : ($days <= 1 ? 'text-red-600' : ($days <= 3 ? 'text-yellow-500' : 'text-green-600')) }}">
                                                    ({{ \Carbon\Carbon::parse($QuotationApplication->begin_register_date)->diffForHumans() }})
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($QuotationApplication->end_register_date)
                                                {{ $QuotationApplication->end_register_date }}<br>
                                                @php
                                                    $days = \Carbon\Carbon::parse($QuotationApplication->end_register_date)->diffInDays(now()) * -1;
                                                @endphp
                                                <span
                                                    class="{{ $days < 0 ? 'text-black' : ($days <= 1 ? 'text-red-600' : ($days <= 3 ? 'text-yellow-500' : 'text-green-600')) }}">
                                                    ({{ \Carbon\Carbon::parse($QuotationApplication->end_register_date)->diffForHumans() }})
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($QuotationApplication->closing_date)
                                                {{ $QuotationApplication->closing_date }}<br>
                                                @php
                                                    $days = \Carbon\Carbon::parse($QuotationApplication->closing_date)->diffInDays(now()) * -1;
                                                @endphp
                                                <span
                                                    class="{{ $days < 0 ? 'text-black' : ($days <= 1 ? 'text-red-600' : ($days <= 3 ? 'text-yellow-500' : 'text-green-600')) }}">
                                                    ({{ \Carbon\Carbon::parse($QuotationApplication->closing_date)->diffForHumans() }})
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($QuotationApplication->slip_path)
                                                <form action="{{ route('download.slip', $QuotationApplication->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                        YES
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400">NO</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <p>{{$QuotationApplication->site_visit_location ? 'YES' : 'NO'}}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $QuotationApplication->site_visit_location ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($QuotationApplication->site_visit_date)
                                                                            {{ $QuotationApplication->site_visit_date }}<br>
                                                                            @php
                                                                                $days =
                                                                                    \Carbon\Carbon::parse($QuotationApplication->site_visit_date)->diffInDays(now())
                                                                                    * -1;
                                                                            @endphp
                                                                            <span
                                                                                class="{{ $days < 0 ? 'text-black' : ($days <= 1 ? 'text-red-600' : ($days <= 3 ? 'text-yellow-500' : 'text-green-600')) }}">
                                                                                ({{
                                                \Carbon\Carbon::parse($QuotationApplication->site_visit_date)->diffForHumans()
                                                                                                                                            }})
                                                                            </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            -
                                        </td>
                                        <td class="px-6 py-4">
                                            Not Set
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$QuotationApplication->status}}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="#"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $QuotationApplicationModelToday->links() }}
                        </div>
                    </div>
                </div>
                <hr class="mt-4 mb-4">
                    <h2 class="text-xl font-bold">Quotations Applied:</h2>
                    <div class="p-2">
                        <div
                            class="relative overflow-x-auto shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                            <div class="w-full pb-4">
                                <label for="table-search" class="sr-only">Search</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="table-search-quotations-applied"
                                        wire:model.live.debounce.300ms="searchApplied"
                                        class="w-full block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Search by Quotation Name, Quotation Title, Organization or Owner">
                                </div>
                            </div>
                            <table
                                class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border-collapse border border-gray-200 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-4">
                                            <div class="flex items-center">
                                                <input id="checkbox-all-search" type="checkbox"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            No
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Organization
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Quotation Name
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Quotation Title
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Specialization(s)
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Begin Registeration Date
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            End Registeration Date
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Closing Date
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Slip
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Require Site Visit?
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Location(Site Visit)
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Date & Time(Site Visit)
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Serial Number(From Email)
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Owner
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($QuotationApplicationModel as $QuotationApplication)
                                        <tr
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="w-4 p-4">
                                                <div class="flex items-center">
                                                    <input id="checkbox-table-search-{{$QuotationApplication->id}}"
                                                        type="checkbox"
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                    <label for="checkbox-table-search-{{$QuotationApplication->id}}"
                                                        class="sr-only">checkbox</label>
                                                </div>
                                            </td>
                                            <th scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ ($QuotationApplicationModel->firstItem() + $loop->index) }}
                                            </th>
                                            <td class="px-6 py-4">
                                                {{$QuotationApplication->organization}}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($QuotationApplication->advert_path)
                                                    <form action="{{ route('download.advert', $QuotationApplication->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                            {{ $QuotationApplication->file_name }}
                                                        </button>
                                                    </form>
                                                @else
                                                    {{ $QuotationApplication->file_name }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                {{$QuotationApplication->title}}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{$QuotationApplication->specializations}}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($QuotationApplication->begin_register_date)
                                                    {{ $QuotationApplication->begin_register_date }}<br>
                                                    @php
                                                        $days = \Carbon\Carbon::parse($QuotationApplication->begin_register_date)->diffInDays(now()) * -1;
                                                    @endphp
                                                    <span
                                                        class="{{ $days < 0 ? 'text-black' : ($days <= 1 ? 'text-red-600' : ($days <= 3 ? 'text-yellow-500' : 'text-green-600')) }}">
                                                        ({{ \Carbon\Carbon::parse($QuotationApplication->begin_register_date)->diffForHumans() }})
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($QuotationApplication->end_register_date)
                                                    {{ $QuotationApplication->end_register_date }}<br>
                                                    @php
                                                        $days = \Carbon\Carbon::parse($QuotationApplication->end_register_date)->diffInDays(now()) * -1;
                                                    @endphp
                                                    <span
                                                        class="{{ $days < 0 ? 'text-black' : ($days <= 1 ? 'text-red-600' : ($days <= 3 ? 'text-yellow-500' : 'text-green-600')) }}">
                                                        ({{ \Carbon\Carbon::parse($QuotationApplication->end_register_date)->diffForHumans() }})
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($QuotationApplication->closing_date)
                                                    {{ $QuotationApplication->closing_date }}<br>
                                                    @php
                                                        $days = \Carbon\Carbon::parse($QuotationApplication->closing_date)->diffInDays(now()) * -1;
                                                    @endphp
                                                    <span
                                                        class="{{ $days < 0 ? 'text-black' : ($days <= 1 ? 'text-red-600' : ($days <= 3 ? 'text-yellow-500' : 'text-green-600')) }}">
                                                        ({{ \Carbon\Carbon::parse($QuotationApplication->closing_date)->diffForHumans() }})
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($QuotationApplication->slip_path)
                                                    <form action="{{ route('download.slip', $QuotationApplication->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                            YES
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400">NO</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <p>{{$QuotationApplication->site_visit_location ? 'YES' : 'NO'}}</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $QuotationApplication->site_visit_location ?: '-' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($QuotationApplication->site_visit_date)
                                                    {{ $QuotationApplication->site_visit_date }}<br>
                                                    @php
                                                        $days = \Carbon\Carbon::parse($QuotationApplication->site_visit_date)->diffInDays(now()) * -1;
                                                    @endphp
                                                    <span
                                                        class="{{ $days < 0 ? 'text-black' : ($days <= 1 ? 'text-red-600' : ($days <= 3 ? 'text-yellow-500' : 'text-green-600')) }}">
                                                        ({{ \Carbon\Carbon::parse($QuotationApplication->site_visit_date)->diffForHumans() }})
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                -
                                            </td>
                                            <td class="px-6 py-4">
                                                Not Set
                                            </td>
                                            <td class="px-6 py-4">
                                                {{$QuotationApplication->status}}
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="#"
                                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $QuotationApplicationModel->links() }}
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>