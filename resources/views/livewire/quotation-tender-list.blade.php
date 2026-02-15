<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Applied Quotation/Tender Listing') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- <input wire:model="url" type="text" placeholder="Enter URL to scrape" class="form-control"> -->
                <button wire:click="scrape"
                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Apply</button>

                <h2 class="text-xl font-bold">Quotation Applied Today:</h2>
                <div class="p-2">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <div class="pb-4 bg-white dark:bg-gray-900">
                            <label for="table-search" class="sr-only">Search</label>
                            <div class="relative mt-1">
                                <div
                                    class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="table-search"
                                    class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Search for items">
                            </div>
                        </div>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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
                                        File Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Title
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
                                        Site Visit
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
                                                                    {{$row_count}}
                                                                </th>
                                                                <td class="px-6 py-4">
                                                                    {{$QuotationApplication->owner}}
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    {{$QuotationApplication->file_name}}
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    {{$QuotationApplication->title}}
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    {{$QuotationApplication->specializations}}
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    {{$QuotationApplication->begin_register_date}}
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    {{$QuotationApplication->end_register_date}}
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    {{$QuotationApplication->closing_date}}
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    <a href="#"
                                                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$QuotationApplication->slip_path
                                    ? 'YES' : 'NO'}}</a>
                                                                </td>
                                                                <td class="px-6 py-4">
                                                                    <a href="#"
                                                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$QuotationApplication->site_visit_location
                                    ? 'YES' : 'NO'}}</a>
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
                                                                    {{$QuotationApplication->serial_number}}
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
                                    @php $row_count++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <h2 class="text-xl font-bold">Quotation Applied:</h2>
                <div class="p-2">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <div class="pb-4 bg-white dark:bg-gray-900">
                            <label for="table-search" class="sr-only">Search</label>
                            <div class="relative mt-1">
                                <div
                                    class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                </div>
                                <input type="text" id="table-search"
                                    class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Search for items">
                            </div>
                        </div>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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
                                        File Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Title
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
                                        Site Visit
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Date
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
                                @foreach ($QuotationApplicationModel as $QuotationApplication)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="w-4 p-4">
                                            <div class="flex items-center">
                                                <input id="checkbox-table-search-{{$QuotationApplication->id}}" type="checkbox"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="checkbox-table-search-{{$QuotationApplication->id}}" class="sr-only">checkbox</label>
                                            </div>
                                        </td>
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{$row_count}}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{$QuotationApplication->owner}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$QuotationApplication->file_name}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$QuotationApplication->title}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$QuotationApplication->specializations}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$QuotationApplication->begin_register_date}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$QuotationApplication->end_register_date}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$QuotationApplication->closing_date}}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="#"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$QuotationApplication->slip_path ? 'YES' : 'NO'}}</a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="#"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{$QuotationApplication->site_visit_location ? 'YES' : 'NO'}}</a>
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
                                            {{$QuotationApplication->serial_number}}
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
                                    @php $row_count++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>