<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .task-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .task-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .task-detail {
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .task-detail:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #6c757d;
            display: inline-block;
            width: 150px;
        }
        .value {
            color: #2c3e50;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">📋 Phân Công Nhiệm Vụ Mới</h1>
    </div>

    <div class="content">
        <p>Xin chào <strong>{{ $userName }}</strong>,</p>

        <p>Quản lý <strong>{{ $managerName }}</strong> đã phân công cho bạn một nhiệm vụ mới. Vui lòng xem chi tiết và hoàn thành đúng thời hạn.</p>

        <div class="task-info">
            <div class="task-title">{{ $taskName }}</div>

            @if($taskDescription)
            <div class="task-detail">
                <span class="label">📝 Mô tả:</span>
                <span class="value">{{ $taskDescription }}</span>
            </div>
            @endif

            <div class="task-detail">
                <span class="label">📅 Ngày giao:</span>
                <span class="value">{{ $dateAssigned }}</span>
            </div>

            @if($deadline)
            <div class="task-detail">
                <span class="label">⏰ Hạn hoàn thành:</span>
                <span class="value" style="color: #dc3545; font-weight: 600;">{{ $deadline }}</span>
            </div>
            @endif
        </div>

        <p>Vui lòng đăng nhập vào hệ thống để xem chi tiết và bắt đầu thực hiện nhiệm vụ.</p>

        <center>
            <a href="{{ $loginUrl }}" class="button">Xem Chi Tiết Nhiệm Vụ</a>
        </center>

        <div class="footer">
            <p>Email này được gửi tự động từ Hệ thống Quản lý KPI</p>
            <p>Vui lòng không trả lời email này</p>
        </div>
    </div>
</body>
</html>

