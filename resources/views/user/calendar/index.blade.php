@extends('layouts.dashboard')

@section('title', 'Lịch')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<style>
    .calendar-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .calendar-title {
        font-size: 24px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }

    .legend {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 3px;
    }

    .legend-label {
        font-size: 14px;
        color: #6c757d;
    }

    #calendar {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .fc-event {
        border-radius: 4px !important;
        border: none !important;
        padding: 2px 4px !important;
        font-size: 12px !important;
    }

    .fc-event-title {
        font-weight: 500 !important;
    }

    .kpi-event {
        background-color: #667eea !important;
        color: white !important;
    }

    .task-event {
        background-color: #17a2b8 !important;
        color: white !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="calendar-container">
                <div class="calendar-header">
                    <h4 class="calendar-title">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        Lịch công việc
                    </h4>
                </div>

                <!-- Legend -->
                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #ffc107;"></div>
                        <span class="legend-label">KPI</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: #17a2b8;"></div>
                        <span class="legend-label">Nhiệm vụ</span>
                    </div>
                </div>

                <!-- Calendar -->
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const events = @json($events);

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'vi',
            buttonText: {
                today: 'Hôm nay',
                month: 'Tháng',
                week: 'Tuần',
                day: 'Ngày'
            },
            events: events.map(event => ({
                id: event.id,
                title: event.title,
                start: event.start,
                end: event.end,
                backgroundColor: event.color,
                borderColor: event.color,
                textColor: 'white',
                extendedProps: {
                    type: event.type,
                    description: event.description,
                    status: event.status
                }
            })),
            eventClick: function(info) {
                const event = info.event;
                const props = event.extendedProps;

                let content = `
                    <div class="p-3">
                        <h6 class="mb-2">${event.title}</h6>
                        <p class="mb-1"><strong>Loại:</strong> ${props.type === 'kpi' ? 'KPI' : 'Nhiệm vụ'}</p>
                        <p class="mb-1"><strong>Trạng thái:</strong> ${props.status.replace('_', ' ')}</p>
                        <p class="mb-1"><strong>Ngày bắt đầu:</strong> ${event.start.toLocaleDateString('vi-VN')}</p>
                        ${event.end ? `<p class="mb-0"><strong>Ngày kết thúc:</strong> ${event.end.toLocaleDateString('vi-VN')}</p>` : ''}
                        ${props.description ? `<hr><p class="mb-0"><strong>Mô tả:</strong><br>${props.description}</p>` : ''}
                    </div>
                `;

                // Create modal
                const modalHtml = `
                    <div class="modal fade" id="eventModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Chi tiết</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    ${content}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Remove existing modal
                const existingModal = document.getElementById('eventModal');
                if (existingModal) {
                    existingModal.remove();
                }

                // Add new modal
                document.body.insertAdjacentHTML('beforeend', modalHtml);

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('eventModal'));
                modal.show();
            },
            eventMouseEnter: function(info) {
                info.el.style.cursor = 'pointer';
            }
        });

        calendar.render();
    });
</script>
@endpush
