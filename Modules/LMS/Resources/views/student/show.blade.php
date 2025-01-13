@extends('layouts.main')
@section('page-title')
    {{ __('Student Detail') }}
@endsection

@section('page-breadcrumb')
    {{ __('Student') }},
    {{ $student->name }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush

@section('page-action')
    @permission('course order create')
        <div class="col-auto">
            <a href="{{ route('course_orders.create', ['student_id' => $student->id]) }}" data-bs-toggle="tooltip"
                class="btn btn-primary btn-sm mt-1" style="margin-right: -20px;" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Create Course Order') }}"><i class="ti ti-plus"></i>
            </a>
        </div>
    @endpermission
    <div class="col-auto">
        <ul class="nav nav-pills nav-fill cust-nav information-tab  " id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="order-tab" data-bs-toggle="pill" data-bs-target="#order"
                    type="button">{{ __('Course Order') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="courses-tab" data-bs-toggle="pill" data-bs-target="#courses"
                    type="button">{{ __('Courses') }}</button>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade active show" id="order" role="tabpanel" aria-labelledby="pills-user-tab-1">
                    <div class="card">
                        <div class="card-body table-border-style">
                            <div class="table-responsive overflow_hidden">
                                <table class="table mb-0 pc-dt-simple" id="assets">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ __('Orders') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Value') }}</th>
                                            <th>{{ __('Payment Type') }}</th>
                                            <th>{{ __('Receipt') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    @if (
                                        (!empty($Course_orders) && count($Course_orders) > 0) ||
                                            (!empty($Course_orders_summarys) && count($Course_orders_summarys) > 0))
                                        <tbody>
                                            @foreach ($Course_orders as $course_order)
                                                <tr>
                                                    <td scope="row">
                                                        @if (\Auth::user()->isAbleTo('course order show'))
                                                            <a href="{{ route('course_orders.show', $course_order->id) }}"
                                                                class="btn btn-outline-primary">
                                                                <span class="btn-inner--text">{{ $course_order->order_id }}
                                                            </a>
                                                        @else
                                                            <span class="btn-inner--text">{{ $course_order->order_id }}
                                                        @endif
                                                    </td>
                                                    <td class="order">
                                                        {{ company_date_formate($course_order->created_at) }}
                                                    </td>
                                                    <td>
                                                        {{ $course_order->name }}
                                                    </td>
                                                    <td>
                                                        {{ currency_format_with_sym($course_order->price) }}
                                                    </td>
                                                    <td>
                                                        {{ $course_order->payment_type }}
                                                    </td>
                                                    <td>
                                                        @if (!empty($course_order->receipt))
                                                            <a href="{{ get_file($course_order->receipt) }}"
                                                                title="Invoice" target="_blank">
                                                                <i class="fas fa-file-invoice"></i>
                                                            </a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <!-- Actions -->
                                                            <div class="actions">
                                                                @if ($course_order->payment_status == 'Pending' && $course_order->payment_type == 'Bank Transfer')
                                                                    <div class="action-btn bg-warning ms-2">
                                                                        <a class="mx-3 btn btn-sm  align-items-center"
                                                                            data-url="{{ route('course.bank.request.edit', $course_order->id) }}"
                                                                            data-ajax-popup="true" data-size="lg"
                                                                            data-bs-toggle="tooltip" title=""
                                                                            data-title="{{ __('Payment Status') }}"
                                                                            data-bs-original-title="{{ __('Payment Status') }}">
                                                                            <i class="ti ti-caret-right text-white"></i>
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                                @permission('course order show')
                                                                    <div class="action-btn bg-warning ms-2">
                                                                        <a href="{{ route('course_orders.show', $course_order->id) }}"
                                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                            data-bs-toggle="tooltip"
                                                                            title="{{ __('Details') }}"> <span
                                                                                class="text-white"> <i
                                                                                    class="ti ti-eye"></i></span></a>
                                                                    </div>
                                                                @endpermission

                                                                @permission('course order delete')
                                                                    <div class="action-btn bg-danger ms-2">
                                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['course_orders.destroy', $course_order->id]]) !!}
                                                                        <a href="#!"
                                                                            class="mx-3 btn btn-sm align-items-center show_confirm"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                                            title="{{ __('Delete') }}">
                                                                            <i class="ti ti-trash text-white"></i>
                                                                        </a>
                                                                        {!! Form::close() !!}
                                                                    </div>
                                                                @endpermission
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @foreach ($Course_orders_summarys as $Course_orders_summary)
                                                <tr>
                                                    @php
                                                        $student = \Modules\LMS\Entities\Student::find(
                                                            $Course_orders_summary->student_id,
                                                        );
                                                    @endphp
                                                    <td>
                                                        <a href="#"
                                                            class="btn btn-outline-primary">{{ $Course_orders_summary->order_id }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ company_date_formate($Course_orders_summary->issue_date) }}
                                                    </td>
                                                    <td>
                                                        {{ $student->name }}
                                                    </td>
                                                    <td>
                                                        {{ currency_format_with_sym($Course_orders_summary->price) }}
                                                    </td>
                                                    <td>
                                                        {{ $Course_orders_summary->status }}
                                                    </td>
                                                    <td>
                                                        -
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <!-- Actions -->
                                                            <div class="actions">
                                                                @permission('course order edit')
                                                                    <div class="action-btn bg-info ms-2">
                                                                        <a href="{{ route('course_orders.edit', $Course_orders_summary->id) }}"
                                                                            class="mx-3 btn btn-sm  align-items-center"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="{{ __('Edit') }}">
                                                                            <i class="ti ti-pencil text-white"></i>
                                                                        </a>
                                                                    </div>
                                                                @endpermission
                                                                @permission('course order delete')
                                                                    <div class="action-btn bg-danger ms-2">
                                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['course_orders_summary.destroy', $Course_orders_summary->id]]) !!}
                                                                        <a href="#!"
                                                                            class="mx-3 btn btn-sm align-items-center show_confirm"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                                            title="{{ __('Delete') }}">
                                                                            <i class="ti ti-trash text-white"></i>
                                                                        </a>
                                                                        {!! Form::close() !!}
                                                                    </div>
                                                                @endpermission
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @else
                                        <tbody>
                                            <tr>
                                                <td colspan="7">
                                                    <div class="text-center">
                                                        <i class="fas fa-folder-open text-primary"
                                                            style="font-size: 48px;"></i>
                                                        <h2>{{ __('Opps') }}...</h2>
                                                        <h6>{{ __('No data Found') }}. </h6>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="courses" role="tabpanel" aria-labelledby="pills-user-tab-2">
                    <div class="card">
                        <div class="card-body table-border-style">
                            <div class="table-responsive overflow_hidden">
                                <table class="table mb-0 pc-dt-simple" id="abc">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ __('#') }}</th>
                                            <th>{{ __('Course') }}</th>
                                            <th>{{ __('Category') }}</th>
                                            <th>{{ __('Chapters') }}</th>
                                            <th>{{ __('Chapters completed') }}</th>
                                            <th>{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    @if (!empty($courses) && count($courses) > 0)
                                        <tbody>
                                            @foreach ($courses as $key => $course)
                                                @php
                                                    $key + 1;
                                                    $chapter = \Modules\LMS\Entities\Chapters::where(
                                                        'course_id',
                                                        $course->id,
                                                    )
                                                        ->get()
                                                        ->pluck('name', 'id');
                                                    $chapterIds = $chapter->keys()->all();
                                                    $chapterstatus = \Modules\LMS\Entities\ChapterStatus::whereIn(
                                                        'chapter_id',
                                                        $chapterIds,
                                                    )
                                                        ->where('student_id', $student->id)
                                                        ->where('course_id', $course->id)
                                                        ->where('status', 'Active')
                                                        ->get()
                                                        ->pluck('chapter_id');
                                                    $complate_chapters = \Modules\LMS\Entities\Chapters::whereIn(
                                                        'id',
                                                        $chapterstatus,
                                                    )
                                                        ->get()
                                                        ->pluck('name');
                                                    $cs_incomplete = \Modules\LMS\Entities\ChapterStatus::whereIn(
                                                        'chapter_id',
                                                        $chapterIds,
                                                    )
                                                        ->where('student_id', $student->id)
                                                        ->where('course_id', $course->id)
                                                        ->where('status', 'Inactive')
                                                        ->get();
                                                @endphp
                                                <tr>
                                                    <td>{{ $key }}</td>
                                                    <td>{{ $course->title }}</td>
                                                    <td>{{ !empty($course->category_id) ? $course->category_id->name : '-' }}
                                                    </td>
                                                    <td>{{ !empty($chapter->implode(', ')) ? $chapter->implode(', ') : '-' }}
                                                    </td>
                                                    <td>{{ !empty($complate_chapters->implode(', ')) ? $complate_chapters->implode(', ') : '-' }}
                                                    </td>
                                                    @if (count($cs_incomplete) == 0)
                                                        <td><span
                                                                class="badge fix_badges bg-primary p-2 px-3 rounded">{{ __('Completed') }}</span>
                                                        </td>
                                                    @else
                                                        <td><span
                                                                class="badge fix_badges bg-warning p-2 px-3 rounded">{{ __('Still Watching') }}</span>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @else
                                        <tbody>
                                            <tr>
                                                <td colspan="7">
                                                    <div class="text-center">
                                                        <i class="fas fa-folder-open text-primary"
                                                            style="font-size: 48px;"></i>
                                                        <h2>{{ __('Opps') }}...</h2>
                                                        <h6>{{ __('No data Found') }}. </h6>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
